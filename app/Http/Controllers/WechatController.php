<?php

namespace App\Http\Controllers;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function ($message) {
            return "F Weixin!";
        });
        return $wechat->server->serve();
    }
}
