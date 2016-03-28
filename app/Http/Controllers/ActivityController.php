<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;

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
        $user = User::inSession();
        $paid = false;
        $information = $activity->informations->where('user_id', $user->id)->first();
        if ($information) {
            $payment = $information->payment;
            if ($payment) {
                $paid = $payment->paid;
            }
        }
        return view('activity-show', compact('activity', 'paid'));
    }

    public function join($id)
    {
        $activity = Activity::findOrFail($id);
        // go to comfirm information
        return redirect()->action('InformationController@index', ['activity' => $id]);
    }

    // public function information($id)
    // {
    //     $activity = Activity::find($id);
    //     $subDistricts = SubDistrict::all();
    //     $housingEstates = HousingEstate::all();
    //     return view('information', compact('activity', 'subDistricts', 'housingEstates'));
    // }
}
