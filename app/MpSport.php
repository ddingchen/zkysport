<?php

namespace App;

use App\MpModel;

/**
 *
 */
class MpSport extends MpModel
{
    protected $table = 'bxlx';

    public function getNameAttribute()
    {
        return $this->bxlx_mc;
    }
}
