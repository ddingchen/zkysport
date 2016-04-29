<?php

namespace App\Http\Controllers;

use App\ChargeRecord;
use App\MpCard;
use App\MpVip;
use App\Payment;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;
use Wechat;

class VipController extends Controller
{

    public function vips()
    {
        $vips = MpVip::all();
        return view('vip', compact('vips'));
    }

    public function detail($vipId)
    {
        $vip = MpVip::findById($vipId);
        return view('vip-detail', compact('vip'));
    }

    public function vipsOfUser()
    {
        $cards = MpCard::byUser(User::inSession())->get();
        return view('user-card', compact('cards'));
    }

    public function account($cardNo)
    {
        $card = MpCard::findByNo($cardNo);
        $user = User::inSession();
        if (!$card->hadByUser($user)) {
            return 'You are not card owner.';
        }
        $qr = $this->getQRUrl($cardNo, $user->salt);
        return view('vip-account', compact('card', 'qr'));
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
            try {
                $card = MpCard::findByNo($cardNo);
            } catch (ModelNotFoundException $e) {
                $validator->errors()->add('invalid_card_info', '信息匹配失败，卡号有误。');
                return;
            }
            // check credentials
            if ($card->tel != $tel) {
                $validator->errors()->add('invalid_card_info', '信息匹配失败，电话号码有误。');
                return;
            }
            // check if card already bound
            if ($card->openId) {
                $validator->errors()->add('card_already_bound', '该卡已被绑定');
                return;
            }
            // can not bind two or more card with same type
            if (User::inSession()->alreadyGotTheVip($card->vip->id)) {
                $validator->errors()->add('same_vip_bind_twice', '已绑定过该卡种的卡（同一微信账号，同一卡种，仅能绑定一次）。');
                return;
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // bind to wxpub
        $card = MpCard::findByNo($request->input('card_no'));
        $card->openId = User::inSession()->openId;
        $card->save();
        return redirect('user/card');
    }

    public function displayChargeForm($cardNo)
    {
        $card = MpCard::findByNo($cardNo);
        if (!$card->hadByUser(User::inSession())) {
            return 'You are not card owner.';
        }
        $vip = $card->vip;
        $mode = 'charge';
        return view('vip-pay', compact('vip', 'mode'));
    }

    public function charge(Request $request, $cardNo)
    {
        $this->validate($request, [
            'buy_num' => 'required|between:1,99',
        ]);
        $card = MpCard::findByNo($cardNo);
        $user = User::inSession();
        if (!$card->hadByUser($user)) {
            return 'You are not card owner.';
        }
        $chargeAmount = $card->vip->unitPrice * $request->input('buy_num');
        $balance = $card->amount + $chargeAmount;
        // payment record
        $payment = Payment::prepare($user, $chargeAmount, 'vip_charge');
        // event record
        ChargeRecord::create([
            'card_no' => $cardNo,
            'amount' => $chargeAmount,
            'balance_after_charge' => $balance,
            'user_id' => $user->id,
            'payment_id' => $payment->id,
        ]);
        // payment flow
        $isProductionEnv = config('app.env') == 'production';
        if ($isProductionEnv) {
            $prepayId = $this->prepareForWechat($payment);
            $request->session()->put('success_callback', 'user/card/' . $cardNo);
            $request->session()->put('fail_callback', 'user/card/' . $cardNo);
            return redirect('payment/wxpub')->with([
                'prepay_id' => $prepayId,
            ]);
        } else {
            $payment->successCallbackForWxpub();
            return redirect('user/card/' . $cardNo);
        }
    }

    public function displayBuyForm(Request $request, $vipId)
    {
        $vip = MpVip::findById($vipId);
        $mode = 'buy';
        $user = User::inSession();
        $name = $request->old('name') ? $request->old('name') : $user->realname;
        $tel = $request->old('tel') ? $request->old('tel') : $user->tel;
        return view('vip-pay', compact('vip', 'mode', 'name', 'tel'));
    }

    public function buy(Request $request, $vipId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'tel' => 'required|digits:11',
            'buy_num' => 'required|between:1,99',
        ]);
        $validator->after(function ($validator) use ($vipId) {
            // can not bind two or more card with same type
            if (User::inSession()->alreadyGotTheVip($vipId)) {
                $validator->errors()->add('same_vip_bind_twice', '已绑定过该卡种的卡（同一微信账号，同一卡种，仅能绑定一次）。');
                return;
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $vip = MpVip::findById($vipId);
        $chargeAmount = $vip->unit_price * $request->input('buy_num');
        // payment record
        $payment = Payment::prepare(User::inSession(), $chargeAmount, 'vip_buy');
        // event record
        ChargeRecord::create([
            'amount' => $chargeAmount,
            'user_id' => User::inSession()->id,
            'payment_id' => $payment->id,
            'is_new' => true,
            'vip_id' => $vipId,
            'name' => $request->input('name'),
            'tel' => $request->input('tel'),
        ]);
        $user = User::inSession();
        $user->realname = $request->input('name');
        $user->tel = $request->input('tel');
        $user->save();
        // payment flow
        $isProductionEnv = config('app.env') == 'production';
        if ($isProductionEnv) {
            $prepayId = $this->prepareForWechat($payment);
            $request->session()->put('success_callback', '/user/card');
            $request->session()->put('fail_callback', '/user/card');
            return redirect('payment/wxpub')->with([
                'prepay_id' => $prepayId,
            ]);
        } else {
            $payment->successCallbackForWxpub();
            return redirect('/user/card');
        }
    }

    public function auth($cardNo, $secret)
    {
        try {
            $card = MpCard::find($cardNo);
        } catch (ModelNotFoundException $e) {
            return false;
        }
        if (!$card->isBound) {
            return false;
        }
        $salt = $card->user->salt;
        $matchSecret = md5($cardNo . $salt);
        return $secret == $matchSecret ? $cardNo : false;
    }

    public function active($cardNo)
    {
        $card = MpCard::findByNo($cardNo);
        if (!$card->hadByUser(User::inSession())) {
            return 'You are not card owner.';
        }
        $card->active();
        return 'success';
    }

    private function getQRUrl($cardNo, $salt)
    {
        $secret = md5($cardNo . $salt);
        $url = config('app.url') . '/card/' . $cardNo . '/auth/' . $secret;
        $imageSrc = "uploads/qr/" . $secret . ".png";
        $savePath = public_path($imageSrc);
        \PHPQRCode\QRcode::png($url, $savePath, 'L', 4, 2);
        return $secret . ".png";
    }

    private function getWechatPaymentAttribute()
    {
        return [
            'notify_url' => config('app.url') . '/payment/notify',
            'trade_type' => 'JSAPI',
            'openid' => User::inSession()->open_id,
        ];
    }

    private function prepareForWechat(Payment $payment)
    {
        $order = new Order(array_merge($this->getWechatPaymentAttribute(), [
            'body' => $payment->title,
            'detail' => $payment->description,
            'out_trade_no' => $payment->out_trade_no,
            'total_fee' => $payment->amount * 100,
        ]));
        $result = Wechat::payment()->prepare($order);
        return $result->prepay_id;
    }
}
