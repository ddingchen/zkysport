<?php

namespace App\Http\Controllers;

use App\BookingOrder;
use App\Information;
use App\User;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request, $type, $sub)
    {
        $globalX = 1;
        switch ($type) {
            case 'book':
                $globalX = 1;
                $leftTabHref = '/history/' . $type . '/finish';
                $rightTabHref = '/history/' . $type . '/unfinish';
                break;
            case 'account':
                $globalX = 2;
                $leftTabHref = '/history/' . $type . '/cost';
                $rightTabHref = '/history/' . $type . '/recharge';
                break;
            case 'activity':
                $globalX = 3;
                $leftTabHref = '/history/' . $type . '/expire';
                $rightTabHref = '/history/' . $type . '/active';
                break;
            default:
                break;
        }
        $globalY = 1;
        if ($sub == 'active' || $sub == 'cost' || $sub == 'unfinish') {
            $globalY = 2;
        }
        $user = User::inSession();
        $content = [];
        if ($type == 'book') {
            $content = BookingOrder::all()->where('user_id', $user->id);
            $content = $content->filter(function ($order, $key) use ($sub) {
                $paid = $order->payment->paid;
                return $sub == 'finish' ? $paid : !$paid;
            });
        }
        if ($type == 'activity') {
            $content = Information::all()->where('user_id', $user->id);
            $content = $content->filter(function ($information, $key) use ($sub) {
                $expired = $information->activity->expired;
                return $sub == 'expire' ? $expired : !$expired;
            });
        }

        $request->session()->put('newpayflow', false);

        return view('history', compact('globalX', 'globalY', 'leftTabHref', 'rightTabHref', 'type', 'content'));
    }
}
