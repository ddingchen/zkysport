<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingOrder extends Model
{
    protected $table = 'booking_orders';

    protected $fillable = ['user_id',
        'sport_id',
        'name',
        'tel',
        'number_of_people',
        'start_from',
        'end_to',
        'booking_area_select_id'];
}
