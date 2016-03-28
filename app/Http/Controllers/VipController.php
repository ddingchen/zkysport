<?php

namespace App\Http\Controllers;

class VipController extends Controller
{
    public function index()
    {
        return view('vip');
    }

    public function create()
    {
        return view('vip-create');
    }

    public function bind()
    {
        return view('vip-bind');
    }
}
