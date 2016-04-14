<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all()->sortBy('expired');
        return view('activity', compact('activities'));
    }

    public function show(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $user = User::inSession();
        $paid = false;
        $allInformations = $activity->informations;
        $numOfJoiners = $allInformations->count();
        $information = $allInformations->where('user_id', $user->id)->first();
        if ($information) {
            $payment = $information->payment;
            if ($payment) {
                $paid = $payment->paid;
            }
        }
        $expired = $activity->expired;
        $published = $activity->published;

        // if seller id exist, store in session
        if ($request->has('seller')) {
            $request->session()->put('sellerId', $request->input('seller'));
        }

        return view('activity-show', compact('activity', 'paid', 'expired', 'published', 'numOfJoiners'));
    }

    public function join(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        // go to comfirm information
        $request->session()->put('newjoinflow', true);
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
