<?php

namespace App\Http\Controllers;

use App\BookingAreaSelect;
use App\Sport;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function index(Request $request)
    {
        $sports = Sport::all();
        $areaSelects = [];
        foreach ($sports as $i => $sport) {
            $areaSelects[$sport->id] = $sport->bookingAreaSelects->toArray();
        }
        // retrive selected areas
        $areas = BookingAreaSelect::find(json_decode($request->input('area_id_list')));
        $selectedAreas = $areas ? $areas->implode('id', ',') : '';
        $selectedAreaNames = $areas ? $areas->implode('title', ',') : '';
        return view('sport', compact('selectedAreas', 'areaSelects', 'selectedAreaNames'));
    }

    public function book(Request $request)
    {
        return $request->all();

        $this->validate($request, [

        ]);
    }
}
