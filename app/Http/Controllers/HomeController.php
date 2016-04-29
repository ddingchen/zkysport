<?php

namespace App\Http\Controllers;

use App\MpCard;
use App\User;

class HomeController extends Controller
{
    public function index()
    {

        // reset booking session
        if (session()->has('book')) {
            session()->forget('book');
        }

        $userId = User::inSession()->id;
        $user = session('wechat.oauth_user');
        $nickname = $user->nickname;
        $avatar = $user->avatar;
        // account information
        $user = User::inSession();
        $vip = $user->activeVip();
        $cardCount = MpCard::byUser($user)->count();
        $amount = 0;
        if ($vip) {
            $card = MpCard::byUser($user)->byVip($vip)->first();
            $amount = $card->amount;
        }
        return view('home', compact('userId', 'nickname', 'avatar', 'cardCount', 'amount'));
    }

    public function playground()
    {
        return getAreaCode('badminton', 1);
    }
}
