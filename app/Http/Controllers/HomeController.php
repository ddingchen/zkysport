<?php

namespace App\Http\Controllers;

use App\User;

class HomeController extends Controller
{
    public function index()
    {
        // return redirect('activity') //->view('activity', compact('activities'))
        // ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        //     ->header('Pragma', 'no-cache')
        //     ->header('Expires', '0');

        $userId = User::inSession()->id;
        $user = session('wechat.oauth_user');
        $nickname = $user->nickname;
        $avatar = $user->avatar;
        return view('home', compact('userId', 'nickname', 'avatar'));
    }
}
