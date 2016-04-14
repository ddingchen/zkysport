<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table = 'informations';

    protected $fillable = ['user_id', 'seller_id', 'paid', 'name', 'tel'];

    protected $dates = ['created_at'];

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

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id');
    }

    public function seller()
    {
        return $this->belongsTo('App\Seller', 'seller_id');
    }
}
