<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['out_trade_no', 'amount', 'user_id', 'product', 'paid', 'paid_at', 'refund', 'refund_at'];

    public function information()
    {
        $this->hasOne('App\Information', 'payment_id');
    }
}
