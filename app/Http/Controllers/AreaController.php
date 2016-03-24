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

    public function store(Request $request, $sportId)
    {
        $allInput = array_merge($request->old(), $request->all());
        $request->session()->flash('book', $allInput);
        return redirect()->action('SportController@index');
    }
}
