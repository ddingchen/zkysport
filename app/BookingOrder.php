<?php

namespace App;

use App\MpAreaManage;
use Illuminate\Database\Eloquent\Model;

class BookingOrder extends Model
{
    protected $table = 'booking_orders';

    protected $fillable = [
        'sn',
        'user_id',
        'sport',
        'name',
        'tel',
        'number_of_people',
        'use_at',
        'start_from',
        'end_to',
        'areas',
        'payment_id',
        'origin_amount',
        'expired_at'];

    protected $dates = ['use_at', 'created_at', 'expired_at'];

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id');
    }

    public function areaManager()
    {
        return $this->hasOne('App\AreaManager', 'order_id');
    }

    public function getAreasDescAttribute()
    {
        $sportConfigSource = collect(config('mp.sport'));
        $sport = $this->sport;
        $areas = explode(',', $this->areas);
        $detailConfig = $sportConfigSource->where('code', $sport)->first();
        $areaDict = $detailConfig['area'];
        $areasDesc = collect($areaDict)->filter(function ($value, $key) use ($areas) {
            return in_array($key, $areas);
        })->implode('/');
        return $areasDesc;
    }

    public function getSportNameAttribute()
    {
        $sportConfigSource = collect(config('mp.sport'));
        $sport = $this->sport;
        $detailConfig = $sportConfigSource->where('code', $sport)->first();
        $sportName = $detailConfig['name'];
        return $sportName;
    }

    public function getStartFromAttribute($value)
    {
        $date = new \Carbon\Carbon($value);
        return $date->format('H:i');
    }

    public function getEndToAttribute($value)
    {
        $date = new \Carbon\Carbon($value);
        return $date->format('H:i');
    }

    public function getIsFinishedAttribute()
    {
        return $this->cancel || $this->payment->paid;
    }

    public function activeBooking()
    {
        $sport = $this->sport;
        $areas = explode(',', $this->areas);
        foreach ($areas as $areaId) {
            $code = getAreaCode($sport, $areaId);
            MpAreaManage::where('sp_sph', $code)->update(['sp_zt' => '预订']);
        }
    }
}
