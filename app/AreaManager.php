<?php

namespace App;

use App\BookingOrder;
use Illuminate\Database\Eloquent\Model;

class AreaManager extends Model
{
    protected $table = 'area_manager';

    protected $dates = ['expired_at'];

    protected $fillable = ['order_id', 'mp_area_manager_record_id', 'expired_at'];

    public function scopeFindByMpBookId($query, $mpBookId)
    {
        return $this->where('mp_area_manager_record_id', $mpBookId)->get()->first();
    }

    public function scopeFindByOrder($query, BookingOrder $order)
    {
        return $this->where('order_id', $order->id);
    }
}
