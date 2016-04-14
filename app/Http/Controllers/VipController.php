<?php

namespace App\Http\Controllers;

use App\ChargeRecord;
use App\Payment;
use App\User;
use Carbon\Carbon;
use DB;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;
use Validator;
use Wechat;

class VipController extends Controller
{
    public function vipByUser()
    {
        $user = User::inSession();
        $openId = $user->openId;
        $vipDist = collect(config('mp.vip'));
        $data = DB::connection('mysql')->select("select hy_bh,hy_hydj from hy where hy_wxkh='" . $openId . "'");
        $cards = collect($data)->map(function ($item, $key) use ($vipDist) {
            $vipInfo = $vipDist->where('id', '' . $item->hy_hydj)->first();
            return [
                'no' => $item->hy_bh,
                'type' => $vipInfo['code'],
                'img' => $vipInfo['card_img_url'],
            ];
        });
        return view('vip', compact('cards'));
    }

    public function detail($cardNo)
    {
        if (!$card = $this->isCardOwner($cardNo)) {
            return 'You are not card owner.';
        }
        $vipInfo = collect(config('mp.vip'))->where('id', '' . $card->Hy_hydj)->first();

        $qrcode = Wechat::qrcode();
        $scene = str_pad($cardNo, 8, '0', STR_PAD_LEFT);
        $result = $qrcode->temporary(intval('1' . $scene), 3600);
        $ticket = $result->ticket;
        $card = [
            'no' => $cardNo,
            'amount' => $card->hy_kje,
            'name' => $vipInfo['name'],
            'desc' => $vipInfo['desc'],
            'qr' => $qrcode->url($ticket),
        ];
        return view('vip-detail', compact('card'));
    }

    public function create()
    {
        return view('vip-create');
    }

    public function displayBindForm()
    {
        return view('vip-bind');
    }

    public function bind(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_no' => 'required|digits:8',
            'tel' => 'required|digits:11',
        ]);
        $validator->after(function ($validator) use ($request) {
            $cardNo = $request->input('card_no');
            $tel = $request->input('tel');
            // check if record exist
            if (!$this->validCardInfo($cardNo, $tel)) {
                $validator->errors()->add('card_no', '信息匹配失败，卡号或电话号码有误。');
                return;
            }
            // check if card already bound
            if ($this->alreadyBoundToWxpub($cardNo)) {
                $validator->errors()->add('card_no', '该卡已被绑定');
                return;
            }
            // can not bind two or more card with same type
            if ($this->alreadyHasTheVip($cardNo)) {
                $validator->errors()->add('card_no', '已绑定过该卡种的卡（同一微信账号，同一卡种，仅能绑定一次）。');
                return;
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // is card owner
        $this->bindCardToWxpub($request->input('card_no'), User::inSession()->openId);
        return redirect('user/vip');
    }

    private function validCardInfo($cardNo, $tel)
    {
        return DB::connection('mysql')->table('hy')->where([
            ['hy_bh', $cardNo],
            ['hy_dh', $tel],
        ])->count() > 0;
    }

    private function alreadyBoundToWxpub($cardNo)
    {
        return DB::connection('mysql')->table('hy')->where([
            ['hy_bh', $cardNo],
        ])->value('hy_wxkh');
    }

    private function alreadyHasTheVip($cardNo)
    {
        $vipType = DB::connection('mysql')->table('hy')->where('hy_bh', $cardNo)->value('hy_hydj');
        return DB::connection('mysql')->table('hy')->where([
            ['hy_wxkh', User::inSession()->openId],
            ['hy_hydj', $vipType],
        ])->count() > 0;
    }

    private function bindCardToWxpub($cardNo, $openId)
    {
        DB::connection('mysql')->update('update hy set hy_wxkh=? where hy_bh=?', [$openId, $cardNo]);
    }

    public function displayChargeForm($cardNo)
    {
        if (!$card = $this->isCardOwner($cardNo)) {
            return 'You are not card owner.';
        }
        $vipInfo = collect(config('mp.vip'))->where('id', '' . $card->Hy_hydj)->first();
        $unitPrice = $vipInfo['unit_price'];
        return view('vip-charge', compact('unitPrice'));
    }

    public function charge(Request $request, $cardNo)
    {
        if (!$card = $this->isCardOwner($cardNo)) {
            return 'You are not card owner.';
        }
        $this->validate($request, [
            'buy_num' => 'required',
            'pay_method' => 'required|between:1,99',
        ]);
        $vipInfo = collect(config('mp.vip'))->where('id', '' . $card->Hy_hydj)->first();
        $unitPrice = $vipInfo['unit_price'];
        $chargeAmount = $unitPrice * $request->input('buy_num');

        $user = User::inSession();
        $tradeNo = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
        $payment = new Payment([
            'amount' => $chargeAmount,
            'purchase_at' => time(),
            'out_trade_no' => $tradeNo,
            'product' => 'vip_charge',
        ]);
        $payment->user()->associate($user);
        $payment->save();
        $chargeRecord = ChargeRecord::create([
            'card_no' => $cardNo,
            'amount' => $chargeAmount,
            'payment_id' => $payment->id,
        ]);

        // payment flow
        $isProductionEnv = config('app.env') == 'production';
        if ($isProductionEnv) {
            $attributes = [
                'body' => 'VIP充值',
                'detail' => '充值¥' . $chargeAmount . '至卡号为' . $cardNo . '的帐户',
                'out_trade_no' => $tradeNo,
                'total_fee' => $chargeAmount * 100,
                'notify_url' => config('app.url') . '/payment/notify',
                'trade_type' => 'JSAPI',
                'openid' => $user->open_id,
            ];

            $order = new Order($attributes);
            $result = Wechat::payment()->prepare($order);
            return redirect('payment/wxpub')->with([
                'prepay_id' => $result->prepay_id,
                'success_callback' => '/vip/' . $cardNo,
                'fail_callback' => '/vip/' . $cardNo,
            ]);
        } else {
            $payment->paid = true;
            $payment->paid_at = Carbon::now();
            $payment->save();
            DB::connection('mysql')->update("update hy set hy_kje=hy_kje+" . $chargeAmount . " where hy_bh='" . $cardNo . "'");
            return redirect('/vip/' . $cardNo);
        }
    }

    private function isCardOwner($cardNo)
    {
        $openId = User::inSession()->openId;
        // card owner check
        $cards = DB::connection('mysql')->table('hy')->where([
            ['hy_wxkh', $openId],
            ['hy_bh', $cardNo],
        ]);
        if ($cards->count() == 0) {
            return false;
        }
        return $cards->first();
    }
}
