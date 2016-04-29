<?php

namespace App\Http\Controllers;

use App\SellProduction;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Raw;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $server = app('wechat')->server;

        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    if ($message->Event == 'subscribe') {
                        $openId = $message->FromUserName;
                        // 扫码报名（未关注）
                        if (substr_count($message->EventKey, 'qrscene_') > 0) {
                            $key = str_replace('qrscene_', '', $message->EventKey);
                            if ($key == 1000000) {
                                return $this->scanQrForGetCard($openId);
                            }
                            return $this->scanQrForJoinMatch($key);
                        }
                    }
                    // 扫码报名（已关注）
                    if ($message->Event == 'SCAN') {
                        $openId = $message->FromUserName;
                        $key = $message->EventKey;
                        if ($key == 1000000) {
                            return $this->scanQrForGetCard($openId);
                        }
                        return $this->scanQrForJoinMatch($key);
                    }
                    break;
                case 'text':
                    if ($message->Content == '游泳') {
                        return new Image(['media_id' => 'LUY0P8mlFZSb6H24mzka7Ht7DxrCx3YB-Y9C7M33ixg']);
                    }
                    break;
                default:
                    break;
            }
        });

        return $server->serve();
    }

    public function menu()
    {
        $menu = app('wechat')->menu;
        // return $menus = $menu->all();
        $buttons = [
            [
                "name" => "园区介绍",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "园区简介",
                        "url" => "http://mp.weixin.qq.com/s?__biz=MzAwNTAyNzEwOQ%3D%3D&mid=201544482&idx=1&sn=f656b4f6e8fc803aeede07affeb55e31&scene=18#rd",
                    ],
                    [
                        "type" => "view",
                        "name" => "招商信息",
                        "url" => "http://mp.weixin.qq.com/s?__biz=MzAwNTAyNzEwOQ%3D%3D&mid=201557102&idx=1&sn=df20907dfd38366d9732e447346f5e3d&scene=18#rd",
                    ],
                ],
            ],
            [
                "name" => "精彩活动",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "绿荫宝贝",
                        "url" => "http://mp.weixin.qq.com/s?__biz=MzAwNTAyNzEwOQ==&mid=403135102&idx=1&sn=7981c20a1960943194d29a26b9030181#rd",
                    ],
                    [
                        "type" => "view",
                        "name" => "AOA动漫",
                        "url" => "http://mp.weixin.qq.com/s?__biz=MzAwNTAyNzEwOQ==&mid=402963428&idx=3&sn=0707e950fb62751eb6c8f838246c2fad#rd",
                    ],
                ],
            ],
            [
                "type" => "view",
                "name" => "我的报名",
                "url" => "http://wap.zhongkaiyun.com/history/activity/active",
            ],
        ];
        $menu->add($buttons);
    }

    public function material()
    {
        $material = app('wechat')->material;
        $lists = $material->lists('image', 0, 20);
        return $lists;
    }

    private function scanQrForJoinMatch($key)
    {
        $key = intval($key);
        $sellerId = floor($key / 1000);
        $productionId = $key % 1000;
        // default product as activity
        $production = SellProduction::find($productionId);
        $activity = $production->activity;
        $news = new News([
            'title' => $production->title,
            'description' => $production->description,
            'url' => url('/activity/' . $production->activity_id . '?seller=' . $sellerId),
            'image' => asset('/uploads/activities/wxpub/' . $activity->banner),
        ]);
        return $news;
    }

    private function scanQrForGetCard($openId)
    {
        $staff = app('wechat')->staff;
        $message = new Raw('{
            "touser":"' . $openId . '",
            "msgtype":"wxcard",
            "wxcard":
            {
                 "card_id":"pJRMds4UiDQjgoyzNhXjNmzdGxpw"
            }
        }');
        $staff->message($message)->to($openId)->send();
    }
}
