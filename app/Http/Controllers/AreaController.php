<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request, $sportId)
    {
        $request->flash();
        return view('area', compact('sportId'));
    }

    // public function store(Request $request, $sportId)
    // {
    //     // var_dump($request->input('area_id_list'));
    //     // return 'hello world';
    // }
}
