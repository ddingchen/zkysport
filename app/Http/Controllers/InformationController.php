<?php

namespace App\Http\Controllers;

use App\Activity;
use App\DetailInformation;
use App\HousingEstate;
use App\Information;
use App\SubDistrict;
use App\User;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index($activityId)
    {
        $activity = Activity::find($activityId);
        $subDistricts = SubDistrict::all();
        $housingEstates = HousingEstate::all();
        return view('information', compact('activity', 'subDistricts', 'housingEstates'));
    }

    public function store(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $user = User::inSession();
        //store main information
        //user already join in, delete exist record
        $existInfomation = $activity->informations->where('user_id', $user->id);
        if ($existInfomation->count() > 0) {
            Information::destroy($existInfomation->lists('id'));
        }
        $information = new Information;
        $information->user()->associate($user);
        $activity->informations()->save($information);
        //store detail information
        $detail = new DetailInformation($request->all());
        $detail->information()->associate($information);
        $detail->save();

        // go to payment page if require
        if ($activity->ticket_price > 0) {
            // $attributes = [
            //     'body' => 'iPad mini 16G 白色',
            //     'detail' => 'iPad mini 16G 白色',
            //     'out_trade_no' => '1217752501201407033233368018',
            //     'total_fee' => 5388,
            //     'notify_url' => 'http://xxx.com/order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            //     // ...
            // ];
            // $order = new Order($attributes);
            // $result = $payment->prepare($order);
            // $prepareId = $result->prepay_id;

            return 'Redirect to payment page.';
        }
        // join successfully
        return 'Join activity successfully.';
    }
}
