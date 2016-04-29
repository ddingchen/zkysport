<?php

namespace App\scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Filter db.mpsport.hydj
 */
class MpVipFilterScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $displayMpIdList = collect(config('mp.vip'))->lists('mpid', 'id')->all();
        return $builder->whereIn('Hydj_bh', $displayMpIdList);
    }
}
