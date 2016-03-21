<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingAreaSelect extends Model
{
    protected $table = 'booking_area_selects';

    protected $fillable = ['sport_id',
        'code',
        'title',
        'sort'];
}
