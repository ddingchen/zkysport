<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $user = session('wechat.oauth_user');
        $nickname = $user->nickname;
        $avatar = $user->avatar;
        return view('home', compact('nickname', 'avatar'));
    }
}
