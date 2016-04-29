<?php

namespace App\Http\Controllers;

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
                $leftTabHref = '/history/' . $type . '/recharge';
                $rightTabHref = '/history/' . $type . '/cost';
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
            if ($sub == 'finish') {
                $content = $user->finishedBookingOrders()->sortByDesc('created_at');
            } else {
                $content = $user->unfinishedBookingOrders()->sortByDesc('created_at');
            }
        } elseif ($type == 'activity') {
            if ($sub == 'expire') {
                $content = $user->joinedExpiredActivities();
            } else {
                $content = $user->joinedActiveActivities();
            }
        } elseif ($type == 'account') {
            if ($sub == 'recharge') {
                $content = $user->mpChargeRecords()->sortByDesc('Jzjl_jssj');
            } else {
                $content = $user->mpConsumeRecords()->sortByDesc('Jzjl_jssj');
            }
        }

        $request->session()->put('newpayflow', false);

        return view('history', compact('globalX', 'globalY', 'leftTabHref', 'rightTabHref', 'type', 'sub', 'content'));
    }
}
