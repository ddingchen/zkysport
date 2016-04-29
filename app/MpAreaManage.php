<?php

namespace App;

use App\MpModel;
use Carbon\Carbon;

/**
 *
 */
class MpAreaManage extends MpModel
{
    protected $table = 'sp';

    protected $primaryKey = 'sp_sph';

    public function scopeFindByCode($query, $code)
    {
        return $query->where('sp_sph', $code)->get()->first();
    }

    public function mpSport()
    {
        return $this->belongsTo('App\MpSport', 'sp_qy', 'bxlx_bh');
    }

    public function mpBookingManage()
    {
        return $this->hasMany('App\MpBookingManage', 'ydgl_spbh', 'sp_sph');
    }

    public function getStatusAttribute()
    {
        return $this->sp_zt;
    }

    public function setStatusAttribute($value)
    {
        $this->sp_zt = $value;
    }

    public function getIsHeldAttribute()
    {
        return $this->status == '占用';
    }

    public function getReleaseAtAttribute()
    {
        $subject = $this->sp_lbdjbz;
        $pattern = '/([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])/';
        $result = preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);
        if ($result) {
            return new Carbon($matches[0][0]);
        }
        return Carbon::now();
    }

    public function updateStatus()
    {
        if ($this->status != '占用') {
            $hasBooking = !$this->mpBookingManage->isEmpty();
            $this->status = $hasBooking ? '预订' : '可供';
            $this->save();
        }
    }
}
