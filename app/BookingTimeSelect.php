<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingTimeSelect extends Model
{
    protected $table = 'booking_time_selects';

    protected $fillable = ['sport_id',
        'start_from',
        'end_from',
        'sort'];
}
