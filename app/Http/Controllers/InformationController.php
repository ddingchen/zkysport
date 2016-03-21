<?php

namespace App\Http\Controllers;

use App\Activity;
use App\DetailInformation;
use App\Information;
use App\Payment;
use App\SubDistrict;
use App\User;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index($activityId)
    {
        $activity = Activity::find($activityId);
        $subDistricts = SubDistrict::all();
        $housingEstates = SubDistrict::first()->housingEstates;
        return view('information', compact('activity', 'subDistricts', 'housingEstates'));
    }

    public function housingEstates($subDistrictId)
    {
        $housingEstates = SubDistrict::find($subDistrictId)->housingEstates;
        return response()->json($housingEstates->toArray());
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
        if ($request->require_information) {
            $detail = new DetailInformation($request->all());
            $detail->information()->associate($information);
            $detail->save();
        }

        // go to payment page if require
        if ($activity->ticket_price > 0) {
            // init payment info
            $tradeNo = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
            $payment = new Payment([
                'amount' => $activity->ticket_price,
                'purchase_at' => time(),
                'out_trade_no' => $tradeNo,
                'product' => 'activity',
            ]);
            $payment->user()->associate($user);
            $payment->save();
            // payment flow
            // $attributes = [
            //     'body' => '赛事报名',
            //     'detail' => $activity->name,
            //     'out_trade_no' => $tradeNo,
            //     'total_fee' => $activity->ticket_price,
            //     'notify_url' => env('APP_URL') . '/payment/notify',
            // ];
            // $order = new Order($attributes);
            // $result = Wechat::payment()->prepare($order);
            // $prepareId = $result->prepay_id;
            // var_dump($prepareId);
            return 'Redirect to payment page.';
        }
        // join successfully
        return 'Join activity successfully.';
    }
}
