<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';

    protected $fillable = ['title', 'banner', 'desc', 'ticket_price', 'require_information', 'start_from', 'end_to', 'expired'];

    public function informations()
    {
        return $this->hasMany('App\Information', 'activity_id');
    }
}
