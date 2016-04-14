<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AreaController extends Controller
{

    public function index(Request $request, $sport)
    {
        // flash request input into session
        $request->flash();
        // area list
        $sportConfigSource = collect(config('mp.sport'));
        $sportAreas = $sportConfigSource->lists('area', 'code')->all();
        $oldInputAreas = $request->input('areas');
        $selectedAreas = collect(json_decode($oldInputAreas));
        // check if any is booked already
        foreach ($sportAreas as $code => &$areas) {
            foreach ($areas as $index => &$area) {
                $faker = \Faker\Factory::create();
                $selected = $selectedAreas->contains($index);
                $area = [
                    'title' => $area,
                    'booked' => !$selected && $faker->boolean(20),
                    'selected' => $selected,
                ];
            }
        }
        return view('area', compact('sport', 'sportAreas', 'oldInputAreas'));
    }

    public function store(Request $request, $sportId)
    {
        $this->validate($request, [
            'areas' => 'required',
        ]);
        $allInput = array_merge($request->old(), $request->all());
        $request->session()->flash('book', $allInput);
        return redirect()->action('SportController@index');
    }
}
