<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ChargeRecord extends Model
{
    protected $table = 'charge_records';

    protected $fillable = ['card_no', 'amount', 'balance_after_charge', 'user_id', 'payment_id', 'is_new', 'vip_id', 'name', 'tel'];

     protected $dates = ['created_at', 'updated_at'];

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id');
    }

    public function card(){
    	return $this->belongsTo('App\MpCard', 'card_no', 'Hy_bh');
    }

    // public function getChargeAtAttribute(){
    // 	$date = new Carbon($this->created_at);
    // 	return $date->format('Y-m-d');
    // }
}
