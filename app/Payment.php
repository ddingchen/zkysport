<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['out_trade_no', 'amount', 'user_id', 'product', 'paid', 'paid_at', 'refund', 'refund_at'];

    public function information()
    {
        return $this->hasOne('App\Information', 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
