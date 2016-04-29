<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Information;
use App\Payment;
use App\SubDistrict;
use App\User;
use Carbon\Carbon;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;
use Wechat;

class InformationController extends Controller
{
    public function index(Request $request, $activityId)
    {
        if (!$request->session()->get('newjoinflow', false)) {
            return redirect('/activity');
        }
        $activity = Activity::find($activityId);
        $user = User::inSession();
        $name = $request->old('name') ? $request->old('name') : $user->realname;
        $tel = $request->old('tel') ? $request->old('tel') : $user->tel;
        $ticketPrice = $activity->ticket_price;
        return response()->view('information', compact('activityId', 'name', 'tel', 'ticketPrice'));
        // ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        // ->header('Pragma', 'no-cache')
        // ->header('Expires', '0');

        // $subDistricts = SubDistrict::all();
        // $housingEstates = SubDistrict::first()->housingEstates;
        // return view('information', compact('activity', 'subDistricts', 'housingEstates'));
    }

    public function housingEstates($subDistrictId)
    {
        $housingEstates = SubDistrict::find($subDistrictId)->housingEstates;
        return response()->json($housingEstates->toArray());
    }

    public function store(Request $request, $activityId)
    {
        if (!$request->session()->get('newjoinflow', false)) {
            return redirect('/activity');
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'tel' => 'required|digits:11',
        ]);

        $activity = Activity::findOrFail($activityId);
        $user = User::inSession();
        //store global user info
        $user->realname = $request->input('name');
        $user->tel = $request->input('tel');
        $user->save();
        //store main information
        //user already join in, delete exist record
        $information = $activity->informations->where('user_id', $user->id)->first();
        if (!$information) {
            $information = new Information;
            $information->user()->associate($user);
            $activity->informations()->save($information);
        }
        $information->name = $request->input('name');
        $information->tel = $request->input('tel');
        if ($request->session()->has('sellerId')) {
            $information->seller_id = $request->session()->get('sellerId');
        }
        $information->save();
        //store detail information
        // if ($request->require_information) {
        //     $detail = new DetailInformation($request->all());
        //     $detail->information()->associate($information);
        //     $detail->save();
        // }

        // go to payment page if require
        if ($activity->ticket_price > 0) {
            // init payment info
            $tradeNo = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
            if ($information->payment) {
                $userPayment = $information->payment;
                if ($userPayment->paid) {
                    return redirect('activity/' . $activityId);
                } else {
                    $userPayment->out_trade_no = $tradeNo;
                    $userPayment->save();
                }
            } else {
                $payment = new Payment([
                    'amount' => $activity->ticket_price,
                    'purchase_at' => time(),
                    'out_trade_no' => $tradeNo,
                    'product' => 'activity',
                ]);
                $payment->user()->associate($user);
                $payment->save();
                $information->payment()->associate($payment);
                $information->save();
            }

            $request->session()->put('newjoinflow', false);
            $request->session()->put('newpayflow', true);
            // payment flow
            $isProductionEnv = config('app.env') == 'production';
            if ($isProductionEnv) {
                $attributes = [
                    'body' => '赛事报名',
                    'detail' => $activity->name,
                    'out_trade_no' => $tradeNo,
                    'total_fee' => $activity->ticket_price * 100,
                    'notify_url' => config('app.url') . '/payment/notify',
                    'trade_type' => 'JSAPI',
                    'openid' => $user->open_id,
                ];

                $order = new Order($attributes);
                $result = Wechat::payment()->prepare($order);
                $request->session()->put('success_callback', '/history/activity/active');
                $request->session()->put('fail_callback', '/activity/' . $activityId);
                return redirect('payment/wxpub')->with([
                    'prepay_id' => $result->prepay_id,
                ]);
            } else {
                $p = $information->payment;
                $p->paid = true;
                $p->paid_at = Carbon::now();
                $p->save();
                return redirect('/history/activity/active');
            }
        }
        // join successfully
        return true;
    }

    // public function GetJsApiParameters($UnifiedOrderResult)
    // {
    //     if (!array_key_exists("appid", $UnifiedOrderResult)
    //         || !array_key_exists("prepay_id", $UnifiedOrderResult)
    //         || $UnifiedOrderResult['prepay_id'] == "") {
    //         throw new WxPayException("参数错误");
    //     }
    //     $jsapi = new WxPayJsApiPay();
    //     $jsapi->SetAppid($UnifiedOrderResult["appid"]);
    //     $timeStamp = time();
    //     $jsapi->SetTimeStamp("$timeStamp");
    //     $jsapi->SetNonceStr($this->getNonceStr());
    //     $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
    //     $jsapi->SetSignType("MD5");
    //     $jsapi->SetPaySign($jsapi->MakeSign());
    //     $parameters = json_encode($jsapi->GetValues());
    //     return $parameters;
    // }

    // /**
    //  *
    //  * 产生随机字符串，不长于32位
    //  * @param int $length
    //  * @return 产生的随机字符串
    //  */
    // public static function getNonceStr($length = 32)
    // {
    //     $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    //     $str = "";
    //     for ($i = 0; $i < $length; $i++) {
    //         $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    //     }
    //     return $str;
    // }
}
