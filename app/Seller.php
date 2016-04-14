<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $table = 'sellers';

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function informations()
    {
        return $this->hasMany('App\Information', 'seller_id');
    }

    public function getSoldInformationsAttribute()
    {
        $informations = Information::where('seller_id', $this->id)
            ->whereHas('payment', function ($query) {
                $query->where('paid', true);
            })->get();
        return $informations;
    }
}
