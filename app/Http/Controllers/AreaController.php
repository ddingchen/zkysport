<?php

namespace App\Http\Controllers;

use App\AreaManager;
use App\MpAreaManage;
use App\MpBookingManage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class AreaController extends Controller
{

    public function index(Request $request, $sport)
    {
        $flashSession = session('book');
        if (!$flashSession) {
            return redirect('sport');
        }
        // booking time
        $date = $flashSession['date'];
        $hourFrom = $flashSession['from'];
        $hourTo = $flashSession['to'];
        $from = new Carbon($date . ' ' . $hourFrom);
        $to = new Carbon($date . ' ' . $hourTo);
        // area list
        $sportConfigSource = collect(config('mp.sport'));
        $sportAreas = $sportConfigSource->lists('area', 'code')->all();
        $oldInputAreas = $flashSession['areas'];
        $selectedAreas = collect(json_decode($oldInputAreas));
        // booked areas
        foreach ($sportAreas as $code => &$areas) {
            foreach ($areas as $index => &$area) {
                $selected = $selectedAreas->contains($index);
                $booked = !MpBookingManage::areaCode($area)->inTimeRange($from, $to)->get()->isEmpty();
                if (Carbon::now()->format('Y-m-d') == $date && !$booked) {
                    \Log::debug($area);
                    $mpAreaManage = MpAreaManage::findByCode($area);
                    if ($mpAreaManage instanceof MpAreaManage && $mpAreaManage->isHeld && $mpAreaManage->releaseAt->gt($from)) {
                        $booked = true;
                    }
                }
                $area = [
                    'title' => $area,
                    'booked' => $booked && !$selected,
                    'selected' => $selected,
                ];
            }
        }
        return view('area', compact('sport', 'sportAreas', 'oldInputAreas'));
    }

    public function store(Request $request, $sportId)
    {
        $flashSession = session('book');
        $sport = $flashSession['sport'];
        $name = $flashSession['name'];
        $tel = $flashSession['tel'];
        $numberOfPeople = $flashSession['num'];
        $date = $flashSession['date'];
        $hourFrom = $flashSession['from'];
        $hourTo = $flashSession['to'];
        $from = new Carbon($date . ' ' . $hourFrom);
        $to = new Carbon($date . ' ' . $hourTo);
        $areas = json_decode($request->input('areas'));
        $newAreas = $areas;
        if (array_key_exists('areas', $flashSession)) {
            $oldAreas = json_decode($flashSession['areas']);
            if (is_array($oldAreas)) {
                $newAreas = array_diff($newAreas, $oldAreas);
            }
        }

        $validator = Validator::make($request->all(), [
            'areas' => 'required|json',
        ]);
        $validator->after(function ($validator) use ($sport, $areas, $newAreas, $from, $to) {
            if (count($areas) == 0) {
                $validator->errors()->add('no_area_found', '请选择场地！');
                return;
            }

            $booked = false;
            foreach ($newAreas as $areaId) {
                $booked = !MpBookingManage::areaCode(getAreaCode($sport, $areaId))->inTimeRange($from, $to)->get()->isEmpty();
                if ($booked) {
                    break;
                }
            }
            if ($booked) {
                $validator->errors()->add('area_already_booked', '场地已被抢先预订,请重新选择。');
                return;
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // clean the old action
        if (array_key_exists('mpBookId', $flashSession)) {
            $mpBookId = $flashSession['mpBookId'];
            AreaManager::where('mp_area_manager_record_id', $mpBookId)->delete();
            MpBookingManage::findById($mpBookId)->delete();
        }
        // store into mp db
        $areaCodes = $this->transToCode($sport, $areas);
        $mpBookId = MpBookingManage::hold($sport, $areaCodes, $name, $tel, $numberOfPeople, $date, $hourFrom, $hourTo);
        // relation data into local db
        AreaManager::create([
            'mp_area_manager_record_id' => $mpBookId,
            'expired_at' => Carbon::now()->addMinutes(20),
        ]);
        // update mp area manager status
        $changedAreas = $areas;
        if ($flashSession['areas']) {
            $changedAreas = array_merge(json_decode($flashSession['areas']), $changedAreas);
        }
        foreach ($changedAreas as $areaId) {
            MpAreaManage::find(getAreaCode($sport, $areaId))->updateStatus();
        }

        $allInput = array_merge($flashSession, $request->all(), ['mpBookId' => $mpBookId]);
        $request->session()->put('book', $allInput);
        return redirect('sport');
    }

    private function transToCode($sport, $areas)
    {
        $areaCodes = [];
        foreach ($areas as $areaId) {
            $areaCodes[] = getAreaCode($sport, $areaId);
        }
        return $areaCodes;
    }

}
