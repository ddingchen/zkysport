<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table = 'informations';

    protected $fillable = ['user_id', 'paid'];

    public function activity()
    {
        return $this->belongsTo('App\Activity', 'activity_id');
    }

    public function detail()
    {

        return $this->hasOne('App\DetailInformation');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
