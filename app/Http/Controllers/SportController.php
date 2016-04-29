<?php

namespace App\Http\Controllers;

use App\AreaManager;
use App\BookingOrder;
use App\MpAreaManage;
use App\MpBookingManage;
use App\MpCard;
use App\MpVip;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SportController extends Controller
{

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {

        // page back session
        $flashInput = session('book');

        // retrive input from old request or flash session
        $user = User::inSession();
        $name = $this->retriveInputFromRequestOrSession('name', $request, $flashInput, $user->realname);
        $tel = $this->retriveInputFromRequestOrSession('tel', $request, $flashInput, $user->tel);
        $sport = $this->retriveInputFromRequestOrSession('sport', $request, $flashInput, '');
        $date = $this->retriveInputFromRequestOrSession('date', $request, $flashInput, '');
        $from = $this->retriveInputFromRequestOrSession('from', $request, $flashInput, '');
        $to = $this->retriveInputFromRequestOrSession('to', $request, $flashInput, '');
        $areas = $this->retriveInputFromRequestOrSession('areas', $request, $flashInput, '');
        $areaType = $this->retriveInputFromRequestOrSession('area_type', $request, $flashInput, '');

        // date select options
        $dateRange = $this->getBookingDateSelectOptions();

        // data source for retrive area 'name'
        $areaSelectsJson = json_encode($this->getAllSportsRole()->lists('area', 'code')->all());

        return view('sport', compact('name', 'tel', 'sport', 'date', 'from', 'to', 'areas', 'areaType', 'dateRange', 'areaSelectsJson'));
    }

    /**
     * @param Request $request
     */
    public function flashInput(Request $request)
    {
        $flashSession = $request->except(['_token']);
        if (session()->has('book')) {
            $flashSession = array_merge(session('book'), $flashSession);
        }
        $request->session()->put('book', $flashSession);
    }

    public function forgetSession()
    {
        session()->forget('book');
    }

    /**
     * assign random area
     * @param Request $request
     * @return mixed
     */
    public function attemptAssignAreaholder(Request $request)
    {
        $this->validate($request, [
            'sport' => 'in:basketball',
            'date' => 'required|date_format:Y-m-d|after:tomorrow|before:' . Carbon::today()->addDays(3),
            'areas' => 'sometimes|json',
            'area_type' => 'in:all,half',
        ]);
        // rule
        $sportRole = $this->getSportRole('basketball');
        $areaGroup = $sportRole['area_group'];
        // areas count can be 0(unselected) or 1(half) or 2(full)
        $areas = json_decode($request->input('areas'));
        if (is_array($areas) && count($areas) > 0) {
            if (count($areas) >= 3) {
                return [];
            }
        }

        // check enough areas supplied
        $booked = false;
        // self areas
        $selfAreas = $areas ? $this->transToCode('basketball', $areas, $areaGroup) : [];
        $date = $request->input('date');
        // attempt assign
        $areaType = $request->input('area_type');
        \Log::debug($selfAreas);
        if (!$assignAbleAreaCode = $this->attemptAssign($areaGroup, $areaType, $date, $selfAreas)) {
            return [];
        }
        $flashSession = session('book');
        // clean old action
        \Log::debug($flashSession);
        if ($flashSession && array_key_exists('mpBookId', $flashSession)) {
            $mpBookId = $flashSession['mpBookId'];
            AreaManager::where('mp_area_manager_record_id', $mpBookId)->delete();
            MpBookingManage::findById($mpBookId)->delete();
        }
        // store into mp db
        $name = $request->input('name');
        $tel = $request->input('tel');
        $mpBookId = MpBookingManage::hold('basketball', [$assignAbleAreaCode], $name, $tel, 0, $date, '09:00', '17:00'); // relation data into local db

        AreaManager::create([
            'mp_area_manager_record_id' => $mpBookId,
            'expired_at' => Carbon::now()->addMinutes(20),
        ]);
        // update mp area manager status
        $changedAreas = $areas;
        if ($flashSession && is_array(json_decode($flashSession['areas'])) && $changedAreas) {
            $changedAreas = array_merge(json_decode($flashSession['areas']), $changedAreas);
        }
        if ($changedAreas) {
            $sport = $request->input('sport');
            foreach ($changedAreas as $areaId) {
                MpAreaManage::find(getAreaCode($sport, $areaId))->updateStatus();
            }
        }

        $allInput = array_merge($request->all(), ['mpBookId' => $mpBookId]);
        $request->session()->put('book', $allInput);
        return $this->transToAreaId($assignAbleAreaCode);
    }

    private function transToAreaId($areaCode)
    {
        $sportRole = $this->getSportRole('basketball');
        $areaDict = array_flip($sportRole['area']);
        $role = $sportRole['area_group'];
        $groups = array_keys($role);
        if (in_array($areaCode, $groups)) {
            $areas = $role[$areaCode];
        } else {
            $areas = [$areaCode];
        }
        $areaIds = [];
        foreach ($areas as $areaCode) {
            $areaIds[] = $areaDict[$areaCode];
        }
        return $areaIds;
    }

    private function attemptAssign($areaGroup, $areaType, $date, $selfAreaCode)
    {
        \Log::debug('attempt');
        if ($areaType == 'all') {
            \Log::debug('all');
            foreach ($areaGroup as $groupCode => $areaCodes) {
                $booked = MpBookingManage::areaCode($groupCode)->bookingAt($date)->count() > 0;
                if ($booked && !in_array($groupCode, $selfAreaCode)) {
                    continue;
                }
                if ($this->allAreaIsUnbooked($areaCodes, $date, $selfAreaCode)) {
                    return $groupCode;
                }
            }
        } else {
            \Log::debug('half');
            // case 'half'
            foreach ($areaGroup as $groupCode => $areaCodes) {
                \Log::debug($groupCode);
                $booked = MpBookingManage::areaCode($groupCode)->bookingAt($date)->count() > 0;
                if ($booked && !in_array($groupCode, $selfAreaCode)) {
                    \Log::debug('is booked, next group');
                    continue;
                } else {
                    \Log::debug('not booked');
                }
                if ($areaCode = $this->searchHalfAreaUnbooked($areaCodes, $date, $selfAreaCode)) {
                    return $areaCode;
                }
            }
        }
        return false;
    }

    private function searchHalfAreaUnbooked($halfAreaCodes, $date, $selfAreaCode)
    {
        \Log::debug('start search sub area');
        foreach ($halfAreaCodes as $areaCode) {
            \Log::debug($areaCode);
            $booked = MpBookingManage::areaCode($areaCode)->bookingAt($date)->count() > 0;
            if (in_array($areaCode, $selfAreaCode) || !$booked) {
                \Log::debug('get success');
                return $areaCode;
            }
        }
        return false;
    }
    private function allAreaIsUnbooked($areaCodes, $date, $selfAreaCode)
    {
        foreach ($areaCodes as $areaCode) {
            $booked = MpBookingManage::areaCode($areaCode)->bookingAt($date)->count() > 0;
            if ($booked && !in_array($areaCode, $selfAreaCode)) {
                return false;
            }
        }
        return true;
    }

    private function transToCode($sport, $areas, $areaGroup)
    {
        $areaCodes = [];
        foreach ($areas as $areaId) {
            $areaCodes[] = getAreaCode($sport, $areaId);
        }
        if (count($areaCodes) >= 2) {
            if (in_array($areaCodes[0], $areaGroup['lq1'])) {
                $areaCodes[] = 'lq1';
            } else {
                $areaCodes[] = 'lq2';
            }
        }
        return $areaCodes;
    }

    /**
     * @param Request $request
     */
    public function book(Request $request)
    {
        $sport = $request->input('sport');
        $sportRole = $this->getSportRole($sport);
        $fromLimit = Carbon::now()->addHours(2)->format('H:i');

        $role = [
            'name' => 'required|max:255',
            'tel' => 'required|digits:11',
            'num' => 'required_if:sport,pingpong|between:1,99',
            'date' => 'required|date_format:Y-m-d|after:' . $sportRole['booking_date_after'] . '|before:' . Carbon::today()->addDays(3),
            'from' => array(
                'required_if:sport,badminton,pingpong',
                'regex:/([01][0-9]|2[0-3]):00/',
                'after:' . $fromLimit,
                'before:22:00',
            ),
            'to' => array(
                'required_if:sport,badminton,pingpong',
                'regex:/([01][0-9]|2[0-3]):00/',
                'after:from'),
            'areas' => 'required',
        ];
        $this->validate($request, $role);

        // flash input to session
        $flashSession = $request->except(['_token']);
        if (session()->has('book')) {
            $flashSession = array_merge(session('book'), $flashSession);
        }
        $request->session()->put('book', $flashSession);

        return redirect('sport/pay');

    }

    /**
     * @return mixed
     */
    public function displayPayForm()
    {
        // retrive input from flash session
        $flashSession = session('book');
        if (!$flashSession) {
            return redirect('sport');
        }
        \Log::debug(session('book'));
        $sport = $flashSession['sport'];
        $sportRole = $this->getSportRole($sport);
        // base info
        $name = $flashSession['name'];
        $tel = $flashSession['tel'];
        $num = $flashSession['num'];
        $num = $sport == 'basketball' ? 1 : $num;
        $date = $flashSession['date'];
        $from = $flashSession['from'] ? $flashSession['from'] : '09:00';
        $flashSession['from'] = $from;
        $to = $flashSession['to'] ? $flashSession['to'] : '22:00';
        $flashSession['to'] = $to;
        $areas = json_decode($flashSession['areas']);
        // area descriptiion
        $areaDict = $sportRole['area'];
        $areasDesc = '';
        if ($sport == 'badminton' || $sport == 'pingpong') {
            $areasDesc = collect($areaDict)->filter(function ($value, $key) use ($areas) {
                return in_array($key, $areas);
            })->implode('/');
            $areasDesc = '第' . $areasDesc;
        } elseif ($sport == 'basketball') {
            $areasDesc = count($areas) == 1 ? '半场' : '全场';
        }
        $sportName = $sportRole['name'];
        $dayDiffForHuman = $this->dayDiffForHuman($date);
        // amount
        $countOfAmHours = 0;
        $countOfPmHours = 0;
        if ($sport != 'basketball') {
            $countOfAmHours = $this->countOfHours($from, $to, $sportRole['booking_time'], true);
            $countOfPmHours = $this->countOfHours($from, $to, $sportRole['booking_time'], false);
        }
        $countOfAreas = count($areas);
        $countOfPeople = $num;
        $isHalf = $countOfAreas == 1;
        $originAmount = $this->calAmount($sport, $countOfAmHours, $countOfPmHours, $countOfAreas, $countOfPeople, $isHalf);

        // find a payable card
        // User::inSession
        $cards = MpCard::byUser(User::inSession())->get();
        $cardExist = !$cards->isEmpty();
        $maxDiscountCard = null;
        $validCardExist = false;
        if ($cardExist) {
            $cards = $cards->filter(function ($card, $key) use ($originAmount) {
                $amount = $originAmount * $card->vip->discount;
                return $card->amount >= $amount;
            });
            $validCardExist = !$cards->isEmpty();
            $maxDiscountCard = $validCardExist ? $cards->first() : null;
            if ($maxDiscountCard) {
                // find max discount
                foreach ($cards as $card) {
                    if ($card->vip->discount < $maxDiscountCard->vip->discount) {
                        $maxDiscountCard = $card;
                    }
                }
            }
        }

        // flash amount to session
        $flashSession['originAmount'] = $originAmount;
        session()->put('book', $flashSession);

        return view('sport-pay', compact('name', 'tel', 'num', 'date', 'from', 'to', 'areasDesc', 'sportName', 'dayDiffForHuman', 'originAmount', 'cardExist', 'validCardExist', 'maxDiscountCard'));
    }

    /**
     * @param Request $request
     */
    public function pay(Request $request)
    {
        $this->validate($request, [
            'pay_method' => 'required|in:wxpub,1,2,3',
        ]);

        $flashSession = session('book');
        $payMethod = $request->input('pay_method');
        $user = User::inSession();
        // save realname tel
        $user->realname = $flashSession['name'];
        $user->tel = $flashSession['tel'];
        $user->save();
        // cal amount by pay method
        $amount = $flashSession['originAmount'];
        if ($payMethod != 'wxpub') {
            $vip = MpVip::findById($payMethod);
            $amount = $amount * $vip->discount;
        }
        // payment record
        $payment = Payment::prepare($user, $amount, 'book', $payMethod);
        // event record
        $bookingOrder = BookingOrder::create([
            // 'sn' => $faker->ean13,
            'user_id' => $user->id,
            'sport' => $flashSession['sport'],
            'name' => $flashSession['name'],
            'tel' => $flashSession['tel'],
            'number_of_people' => $flashSession['num'],
            'use_at' => $flashSession['date'],
            'start_from' => $flashSession['from'],
            'end_to' => $flashSession['to'],
            'areas' => collect(json_decode($flashSession['areas']))->implode(','),
            'payment_id' => $payment->id,
            'origin_amount' => $flashSession['originAmount'],
            'expired_at' => Carbon::now()->addMinutes(20),
        ]);

        \Log::debug($flashSession);

        // update area manage
        $mpBookId = $flashSession['mpBookId'];
        $areaManager = AreaManager::findByMpBookId($mpBookId);
        $areaManager->order_id = $bookingOrder->id;
        \Log::debug('id=' . $bookingOrder->id);
        $areaManager->expired_at = Carbon::now()->addMinutes(20);
        $areaManager->save();

        // forget expired session
        session()->forget('book');
        // payment flow
        $isProductionEnv = config('app.env') == 'production';
        if ($isProductionEnv) {
            $prepayId = $this->prepareForWechat($payment);
            $request->session()->put('success_callback', '/history/book/finish');
            $request->session()->put('fail_callback', '/history/book/unfinish');
            return redirect('payment/wxpub')->with([
                'prepay_id' => $prepayId,
            ]);
        } else {
            $payment->successCallbackForWxpub();
            return redirect('history/book/finish');
        }
    }

    public function payAgain($orderId)
    {
        $order = BookingOrder::find($orderId);
        if ($order->isFinished) {
            return '';
        }
        $payment = $order->payment;
        $isProductionEnv = config('app.env') == 'production';
        if ($isProductionEnv) {
            $prepayId = $this->prepareForWechat($payment);
            $request->session()->put('success_callback', '/history/book/finish');
            $request->session()->put('fail_callback', '/history/book/unfinish');
            return redirect('payment/wxpub')->with([
                'prepay_id' => $prepayId,
            ]);
        } else {
            $payment->successCallbackForWxpub();
            return redirect('history/book/finish');
        }
    }

    /**
     * @param $sport
     * @param $countOfAmHours
     * @param $countOfPmHours
     * @param $countOfAreas
     * @param $countOfPeople
     * @param $isHalf
     * @return mixed
     */
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

    /**
     * @param $from
     * @param $to
     * @param $dict
     * @param $isAm
     * @return mixed
     */
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

    private function getAllSportsRole()
    {
        return collect(config('mp.sport'));
    }

    /**
     * @param $code
     * @return mixed
     */
    private function getSportRole($code)
    {
        return $this->getAllSportsRole()->where('code', $code)->first();
    }

    /**
     * @return mixed
     */
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

    /**
     * @param $date
     * @return mixed
     */
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

    /**
     * @param $inputName
     * @param $oldRequest
     * @param $flashSession
     * @param $default
     * @return mixed
     */
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
}
