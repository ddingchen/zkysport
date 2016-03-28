<?php

namespace App\Http\Controllers;

use App\Payment;
use Log;
use Wechat;

class PaymentController extends Controller
{

    public function payByWxpub()
    {

        $jsApiParameters = Wechat::payment()->configForPayment(session('prepay_id'));
        $callback = session('wxpub_success');
        Log::info('callback=' . $callback);
        return view('pay-weixin', compact('jsApiParameters', 'callback'));
    }

    public function notify()
    {
        $response = Wechat::payment()->handleNotify(function ($notify, $successful) {
            Log::info('Notify callback:out_trade_no=' . $notify->out_trade_no);
            Log::info('Notify callback:successful=' . $successful);

            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $payment = Payment::where('out_trade_no', $notify->out_trade_no)->first();

            if (!$payment) { // 如果订单不存在
                return 'order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($payment->paid) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                $payment->paid_at = time(); // 更新支付时间为当前时间
                $payment->paid = true;
            }
            $payment->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }
}
