<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $table = 'sports';

    protected $fillable = [
        'id',
        'name',
        'require_number_of_people',
        'area_choose_type',
        'booking_time_type'];

    public function bookingTimeSelects()
    {
        return $this->hasMany('App\BookingTimeSelect');
    }

    public function bookingAreaSelects()
    {
        return $this->hasMany('App\BookingAreaSelect');
    }
}
