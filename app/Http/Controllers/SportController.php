<?php

namespace App\Http\Controllers;

use App\BookingOrder;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function index(Request $request)
    {
        // $today = Carbon::now();
        // echo $today->toDateString() . '今天';
        // echo $today->copy()->addDay()->toDateString() . '明天';
        // echo $today->copy()->addDays(2)->toDateString() . '后天';
        // exit();

        $flashInput = session('book');
        $request->session()->forget('book');

        // retrive 'realname','tel' from old request or flash session
        $user = User::inSession();
        $name = $this->retriveInputFromRequestOrSession('name', $request, $flashInput, $user->realname);
        $tel = $this->retriveInputFromRequestOrSession('tel', $request, $flashInput, $user->tel);
        // date select options
        $dateRange = $this->getBookingDateSelectOptions();
        // sport item select options
        $sportConfigSource = collect(config('mp.sport'));
        $sports = $sportConfigSource->lists('name', 'code')->all();
        // data source for retrive 'name'
        $areaSelectsJson = json_encode($sportConfigSource->lists('area', 'code')->all());
        // dd($dateRange);
        $request->session()->put('newbookflow', true);
        return view('sport', compact('name', 'tel', 'dateRange', 'areaSelectsJson', 'flashInput'));
    }

    public function attemptAssignAreaholder(Request $request)
    {
        $request->session()->reflash();
        $sport = $request->input('sport');
        $areaType = $request->input('area_type');
        $sportConfigSource = collect(config('mp.sport'));
        $allAreas = $sportConfigSource->lists('area', 'code')->get($sport);
        $count = ($sport == 'basketball' && $areaType == 'all') ? 2 : 1;
        $randomAreas = collect($allAreas)->random($count);
        if ($count == 1) {
            $randomAreas = collect($randomAreas);
        }
        return $randomAreas->values()->all();
    }

    private function getBookingDateSelectOptions()
    {
        $today = Carbon::now();
        $dateRange = [
            $today->toDateString() => '今天',
            $today->copy()->addDay()->toDateString() => '明天',
            $today->copy()->addDays(2)->toDateString() => '后天',
        ];
        return $dateRange;
    }

    private function dayDiffForHuman($date)
    {
        $diffDays = Carbon::today()->diffInDays(Carbon::createFromFormat('Y-m-d', $date));
        $dayDiffForHuman;
        switch ($diffDays) {
            case 0:
                $dayDiffForHuman = '今天';
                break;
            case 1:
                $dayDiffForHuman = '明天';
                break;
            case 2:
                $dayDiffForHuman = '后天';
                break;
            default:
                break;
        }
        return $dayDiffForHuman;
    }

    private function retriveInputFromRequestOrSession($inputName, $oldRequest, $flashSession, $default = '')
    {
        $value = $default;
        if ($oldRequest->old($inputName)) {
            $value = $oldRequest->old($inputName);
        } elseif ($flashSession) {
            $value = $flashSession[$inputName];
        }
        return $value;
    }

    public function book(Request $request)
    {
        if (!$request->session()->get('newbookflow', false)) {
            return redirect('/sport');
        }
        $sportConfigSource = collect(config('mp.sport'));
        $sport = $request->input('sport');
        $detailConfig = $sportConfigSource->where('code', $sport)->first();
        // base info validation
        $role = [
            'name' => 'required|max:255',
            'tel' => 'required|digits:11',
            'num' => 'required_if:sport,pingpong|between:1,99',
            'date' => 'required|date_format:Y-m-d|after:' . $detailConfig['booking_date_after'] . '|before:' . Carbon::today()->addDays(3),
            'from' => array(
                'required_if:sport,badminton,pingpong',
                'regex:/([01][0-9]|2[0-3]):00/',
                'after:08:59',
                'before:22:00',
            ),
            'to' => array(
                'required_if:sport,badminton,pingpong',
                'regex:/([01][0-9]|2[0-3]):00/',
                'after:from'),
            'areas' => 'required',
        ];
        $this->validate($request, $role);

        $name = $request->input('name');
        $tel = $request->input('tel');
        $num = $request->input('num');
        $num = $sport == 'basketball' ? 1 : $num;
        $date = $request->input('date');
        $from = $request->input('from') ? $request->input('from') : '09:00';
        $to = $request->input('to') ? $request->input('to') : '22:00';
        $areas = json_decode($request->input('areas'));
        $areaDict = $detailConfig['area'];
        $areasDesc = '';
        // if ($sport == 'badminton' || $sport == 'pingpong') {
        $areasDesc = collect($areaDict)->filter(function ($value, $key) use ($areas) {
            return in_array($key, $areas);
        })->implode('/');
        // } elseif ($sport == 'basketball') {
        //     $areasDesc = count($areas) == 1 ? '半场' : '全场';
        // }
        $sportName = $detailConfig['name'];
        $dayDiffForHuman = $this->dayDiffForHuman($date);
        $countOfAmHours = 0;
        $countOfPmHours = 0;
        if ($sport != 'basketball') {
            $countOfAmHours = $this->countOfHours($from, $to, $detailConfig['booking_time'], true);
            $countOfPmHours = $this->countOfHours($from, $to, $detailConfig['booking_time'], false);
        }
        $countOfAreas = count($areas);
        $countOfPeople = $num;
        $isHalf = $countOfAreas == 1;
        $amount = $this->calAmount($sport, $countOfAmHours, $countOfPmHours, $countOfAreas, $countOfPeople, $isHalf);
        $inputs = $request->except(['_token']);
        $inputs['amount'] = $amount;
        $request->session()->flash('order', $inputs);
        return view('sport-pay', compact('name', 'tel', 'num', 'date', 'from', 'to', 'areasDesc', 'sportName', 'dayDiffForHuman', 'amount'));
    }

    public function pay(Request $request)
    {
        if (!$request->session()->get('newbookflow', false)) {
            return redirect('/sport');
        }
        $orderInfo = session('order');
        $payMethod = $request->input('pay_method');
        $user = User::inSession();
        $user->realname = $orderInfo['name'];
        $user->tel = $orderInfo['tel'];
        $user->save();
        $faker = \Faker\Factory::create();
        $bookingOrder = BookingOrder::create([
            'sn' => $faker->ean13,
            'user_id' => $user->id,
            'sport' => $orderInfo['sport'],
            'name' => $orderInfo['name'],
            'tel' => $orderInfo['tel'],
            'number_of_people' => $orderInfo['num'],
            'use_at' => $orderInfo['date'],
            'start_from' => $orderInfo['from'],
            'end_to' => $orderInfo['to'],
            'areas' => collect(json_decode($orderInfo['areas']))->implode(','),
            // 'payment_id',
        ]);
        $tradeNo = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $orderInfo['amount'],
            'purchase_at' => time(),
            'out_trade_no' => $tradeNo,
            'product' => 'activity',
        ]);
        $bookingOrder->payment()->associate($payment);
        $bookingOrder->save();

        //test
        $payment->paid = true;
        $payment->paid_at = Carbon::now();
        $payment->save();

        $request->session()->put('newbookflow', false);
        return redirect('/history/book/finish');
    }

    private function calAmount($sport, $countOfAmHours, $countOfPmHours, $countOfAreas, $countOfPeople, $isHalf)
    {
        $amount = 0;
        switch ($sport) {
            case 'badminton':
                $amount = ($countOfAreas * $countOfAmHours * 20) + ($countOfAreas * $countOfPmHours * 30);
                break;
            case 'pingpong':
                $amount = ($countOfPeople * $countOfAmHours * 15) + ($countOfPeople * $countOfPmHours * 20);
                break;
            case 'basketball':
                $amount = $isHalf ? 300 : 600;
                break;
            default:
                break;
        }
        return $amount;
    }

    private function countOfHours($from, $to, $dict, $isAm)
    {
        $bookFrom = Carbon::createFromFormat('H:i', $from)->hour;
        $bookTo = Carbon::createFromFormat('H:i', $to)->hour;
        $bookList = range($bookFrom, $bookTo - 1);
        $range = $dict[$isAm ? 0 : 1];
        $rangeFrom = Carbon::createFromFormat('H:i', $range['start_from'])->hour;
        $rangeTo = Carbon::createFromFormat('H:i', $range['end_to'])->hour;
        $rangeList = range($rangeFrom, $rangeTo - 1);
        $count = count(array_intersect($bookList, $rangeList));
        return $count;

    }

}
