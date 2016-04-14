<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellProduction extends Model
{
    protected $table = 'sell_productions';

    protected $fillable = ['title', 'description', 'activity_id'];

    public function activity()
    {
        return $this->belongsTo('App\Activity', 'activity_id');
    }

}
