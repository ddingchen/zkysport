<?php

namespace App\Http\Controllers;

use App\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        return view('activity', compact('activities'));
    }

    public function show($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activity-show', compact('activity'));
    }

    public function join($id)
    {
        $activity = Activity::findOrFail($id);
        // go to information page if require
        if ($activity->require_information) {
            return redirect()->action('InformationController@index', ['activity' => $id]);
        }
        // go to payment page if require
        if ($activity->ticket_price > 0) {
            return 'Redirect to payment page.';
        }
        // join successfully
        return 'Join activity successfully.';
    }

    // public function information($id)
    // {
    //     $activity = Activity::find($id);
    //     $subDistricts = SubDistrict::all();
    //     $housingEstates = HousingEstate::all();
    //     return view('information', compact('activity', 'subDistricts', 'housingEstates'));
    // }
}
