<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeRecord extends Model
{
    protected $table = 'charge_records';

    protected $fillable = ['card_no', 'amount', 'payment_id'];

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id');
    }
}
