<?php

namespace App\Http\Controllers;

use Wechat;

class PaymentController extends Controller
{

    public function notify()
    {

        $response = Wechat::payment()->handleNotify(function ($notify, $successful) {
            // 你的逻辑
            return true; // 或者错误消息
        });

        return $response; // 或者 $response->send()
    }
}
