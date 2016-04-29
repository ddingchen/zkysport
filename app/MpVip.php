<?php

namespace App;

use App\MpModel;
use App\scope\MpVipFilterScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MpVip extends MpModel
{
    protected $table = 'hydj';

    protected $primaryKey = 'Hydj_bh';

    private $configData;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MpVipFilterScope);
    }

    public function getIdAttribute()
    {
        return $this->getConfigData()['id'];
    }

    public function getMpIdAttribute()
    {
        return $this->Hydj_bh;
    }

    public function getNameAttribute()
    {
        return $this->getConfigData()['name'];
    }

    public function getImageAttribute()
    {
        return $this->getConfigData()['img'];
    }

    public function getUnitPriceAttribute()
    {
        return $this->getConfigData()['unit_price'];
    }

    public function getDiscountAttribute()
    {
        return $this->getConfigData()['discount'];
    }

    public function getDescAttribute()
    {
        return $this->getConfigData()['desc'];
    }

    public function scopeFindById($query, $id)
    {
        $mpIdDict = collect(config('mp.vip'))->lists('mpid', 'id');
        if (!$mpIdDict->has($id)) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model));
        }
        $model = $query->where('Hydj_bh', $mpIdDict->get($id))->first();
        if (!$model) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model));
        }
        return $model;
    }

    private function getConfigData()
    {
        if ($this->configData) {
            return $this->configData;
        }
        $this->configData = collect(config('mp.vip'))->where('mpid', strval($this->Hydj_bh))->first();
        return $this->configData;
    }
}
