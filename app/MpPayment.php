<?php

namespace App;

use App\MpModel;

class MpPayment extends MpModel
{

    protected $table = 'jzjl';

    protected $primaryKey = 'Jzjl_ywbh';

    protected $fillable = [
        'Jzjl_ywbh',
        'Jzjl_sph',
        'jzjl_rs',
        'jzjl_jdsj',
        'jzjl_jsrq',
        'jzjl_jsyear',
        'jzjl_jsmonth',
        'jzjl_total',
        'Jzjl_xfje',
        'Jzjl_dzje',
        'jzjl_jsje',
        'Jzjl_ssje',
        'Jzjl_jssj',
        'Jzjl_jsfs',
        'Jzjl_xykh',
        'jzjl_zt',
        'Jzjl_jzzt',
        'jzjl_hybh',
        'jzjl_hyxm',
        'jzjl_xj',
        'jzjl_czk',
    ];

    protected $dates = ['jzjl_jdsj', 'jzjl_jsrq', 'Jzjl_jssj'];

    protected $attributes = [
        'jzjl_khbh' => null,
        'Jzjl_skr' => 'wxpub',
        'Jzjl_ysje' => 0,
        'Jzjl_mlje' => 0,
        'Jzjl_fjje' => 0,
        'jzjl_bz' => null,
        'jzjl_sby' => null,
        'jzjl_dby' => null,
        'jzjl_fby' => null,
        'jzjl_djq' => 0,
        'jzjl_xyk' => 0,
        'jzjl_yzk' => 0,
        'jzjl_bmbh' => null,
        'jzjl_source' => null,
        'jzjl_shop' => null,
        'jzjl_sfjb' => null,
        'Jzjl_xykh' => '*',
    ];

    public function getPaidAtAttribute()
    {
        return $this->Jzjl_jssj;
    }

    public function getIsChargeAttribute()
    {
        return $this->jzjl_zt == '充值卡充值';
    }

    public function getAmountAttribute()
    {
        return $this->Jzjl_xfje;
    }

    public function mpCard()
    {
        return $this->belongsTo('App\MpCard', 'jzjl_hybh');
    }

    public function mpAreas()
    {
        $areaCodes = collect(explode('/', $this->Jzjl_sph));
        $mpAreas = $areaCodes->map(function ($areaCode) {
            return MpAreaManage::findByCode($areaCode);
        });
        return $mpAreas;
    }

    public function mpAreasDesc()
    {
        $areaCodes = collect(explode('/', $this->Jzjl_sph));
        $mpArea = MpAreaManage::findByCode($areaCodes[0]);
        $sportName = $mpArea->mpSport->name;
        return strpos($sportName, '篮球') ? $sportName : $this->Jzjl_sph . $sportName;
    }
}
