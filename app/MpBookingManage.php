<?php

namespace App;

use App\MpModel;
use Carbon\Carbon;

/**
 *
 */
class MpBookingManage extends MpModel
{

    protected $table = 'ydgl';

    protected $primaryKey = 'ydgl_ydbh';

    protected $fillable = [
        'ydgl_ydbh',
        'ydgl_khxm',
        'ydgl_jp',
        'ydgl_ydfs',
        'ydgl_lxdh',
        'ydgl_yjrs',
        'ydgl_qysj',
        'ydgl_blsj',
        'Ydgl_ydsj',
        'ydgl_ydgg',
        'ydgl_spbh',
        'ydgl_ydzt',
        'ydgl_zdqx',
        'Ydgl_bz',
        'ydgl_sby',
        'ydgl_dby',
        'ydbl_fby',
        'ydgl_hybh',
        'ydgl_Area',
        'ydgl_gsmc',
        'ydgl_ylsj',
        'ydgl_doc',
        'ydgl_zt',
        'ydgl_fssj',
    ];

    protected $dates = ['ydgl_qysj', 'ydgl_blsj', 'Ydgl_ydsj', 'ydgl_ylsj', 'ydgl_fssj'];

    protected $attributes = [
        // 'ydgl_ydbh',
        // 'ydgl_khxm',
        // 'ydgl_jp',
        'ydgl_ydfs' => null,
        // 'ydgl_lxdh',
        // 'ydgl_yjrs',
        // 'ydgl_qysj',
        // 'ydgl_blsj',
        // 'Ydgl_ydsj',
        // 'ydgl_ydgg',
        // 'ydgl_spbh',
        'ydgl_ydzt' => 'Y',
        'ydgl_zdqx' => 'Y',
        'Ydgl_bz' => '无',
        'ydgl_sby' => null,
        'ydgl_dby' => null,
        'ydbl_fby' => null,
        'ydgl_hybh' => '普通宾客',
        'ydgl_Area' => '无',
        'ydgl_gsmc' => '无',
        // 'ydgl_ylsj',
        'ydgl_doc' => null,
        'ydgl_zt' => 'Y',
        // 'ydgl_fssj',
    ];

    public function getAreaCodeAttribute()
    {
        return $this->ydgl_spbh;
    }

    public function scopeAreaCode($query, $code)
    {
        return $query->where('ydgl_spbh', $code);
    }

    public function scopeAreaCodes($query, array $codes)
    {
        return $query->whereIn('ydgl_spbh', $codes);
    }

    public function scopeBookingAt($query, $date)
    {
        return $query->whereDate('ydgl_qysj', '=', $date);
    }

    public function scopeInTimeRange($query, $from, $to)
    {
        return $query->where([
            ['ydgl_qysj', '>=', $from],
            ['ydgl_qysj', '<', $to],
        ])->orWhere([
            ['ydgl_ylsj', '>', $from],
            ['ydgl_ylsj', '<=', $to],
        ]);
    }

    public function scopeHold($query, $sport, $areas, $name, $tel, $numberOfPeople, $date, $from, $to)
    {
        $now = Carbon::now();
        $bookFrom = new Carbon($date . ' ' . $from);
        $bookTo = new Carbon($date . ' ' . $to);
        $id = $now->format('ymdHis') . $now->micro;
        $holdUntil = $bookFrom->copy()->addMinutes(10);
        $sportName = getMpSportName($sport, $areas[0]);

        $attribute = [
            'ydgl_ydbh' => $id,
            'ydgl_khxm' => $name,
            'ydgl_jp' => getHeadCharOfPY($name),
            'ydgl_lxdh' => $tel,
            'ydgl_yjrs' => $numberOfPeople,
            'ydgl_qysj' => $bookFrom,
            'ydgl_blsj' => $holdUntil,
            'Ydgl_ydsj' => $now,
            'ydgl_ydgg' => $sportName,
            'ydgl_spbh' => null,
            'ydgl_ylsj' => $bookTo,
            'ydgl_fssj' => $now,
        ];
        // $mpBookingManageIds = [];
        foreach ($areas as $areaCode) {
            // $newId = $id . rand(0, 999);
            // $attribute['ydgl_ydbh'] = $newId;
            // $areaCode = getAreaCode($sport, $areaId);
            $attribute['ydgl_spbh'] = $areaCode;
            MpBookingManage::create($attribute);
            // $mpBookingManageIds[] = $newId;
        }
        return $id;
    }

    public function scopeFindById($query, $id)
    {
        return $query->where('ydgl_ydbh', $id);
    }
}
