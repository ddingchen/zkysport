<?php

namespace App\Http\Controllers;

use App\Activity;
use App\DetailInformation;
use App\HousingEstate;
use App\Information;
use App\SubDistrict;
use App\User;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index($activityId)
    {
        return config_path('administrator');
        $activity = Activity::find($activityId);
        $subDistricts = SubDistrict::all();
        $housingEstates = HousingEstate::all();
        return view('information', compact('activity', 'subDistricts', 'housingEstates'));
    }

    public function store(Request $request, $activityId)
    {
        $activity = Activity::find($activityId);
        $information = new Information;
        $information->user()->associate(User::inSession());
        //$information->detail()->save(new DetailInformation($request->all()));
        $activity->informations()->save($information);
        $detailInformation = new DetailInformation($request->all());
        $detailInformation->information()->associate($information);

        // go to payment page if require
        if ($activity->ticket_price > 0) {
            return 'Redirect to payment page.';
        }
        // join successfully
        return 'Join activity successfully.';
    }
}
