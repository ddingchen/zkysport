<?php

namespace App\Http\Controllers;

use App\BookingAreaSelect;
use App\Sport;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function index(Request $request)
    {
        $flashInput = session('book');
        // retrive 'realname','tel'
        $user = User::inSession();
        $name = $user->realname;
        if ($request->old('name')) {
            $name = $request->old('name');
        } elseif ($flashInput) {
            $name = $flashInput['name'];
        }
        $tel = $user->tel;
        if ($request->old('tel')) {
            $tel = $request->old('tel');
        } elseif ($flashInput) {
            $tel = $flashInput['tel'];
        }

        // date select
        $today = Carbon::now();
        $dateRange = [
            $today->toDateString() => '今天',
            $today->copy()->addDay()->toDateString() => '明天',
            $today->copy()->addDays(2)->toDateString() => '后天',
        ];
        $sports = Sport::all();
        // area select component data source
        $areaSelects = [];
        // data source for retrive 'name'
        $areaSelectsJson = [];
        foreach ($sports as $i => $sport) {
            $areaSelects[$sport->id] = $sport->bookingAreaSelects->toArray();
            $areaSelectsJson[$sport->id] = $sport->bookingAreaSelects->lists('title', 'id')->all();
        }
        $areaSelectsJson = json_encode($areaSelectsJson);
        // retrive selected areas
        $areas = BookingAreaSelect::find(json_decode($request->input('area_id_list')));
        $selectedAreas = $areas ? $areas->implode('id', ',') : '';
        // $selectedAreaNames = $areas ? $areas->implode('title', ',') : '';
        return view('sport', compact('name', 'tel', 'dateRange', 'selectedAreas', 'areaSelectsJson', 'areaSelects', 'flashInput'));
    }

    public function book(Request $request)
    {
        $sport = Sport::findOrFail($request->input('sport_id'));
        // base info validation
        $role = [
            'name' => 'required|max:255',
            'tel' => 'required|digits:11',
            'num' => 'required_if:sport_id,2|between:1,99',
            'date' => 'required|date_format:Y-m-d|after:' . $sport->booking_date_after . '|before:' . Carbon::today()->addDays(3),
            'from' => array(
                'required_if:sport_id,1,2',
                'regex:/([01][0-9]|2[0-3]):00/',
                'after:08:59',
                'before:22:00',
            ),
            'to' => array(
                'required_if:sport_id,1,2',
                'regex:/([01][0-9]|2[0-3]):00/',
                'after:from'),
        ];
        $this->validate($request, $role);

        //$request->flash();
        return back();
    }

    public function bookingTime($sportId)
    {

    }
}
