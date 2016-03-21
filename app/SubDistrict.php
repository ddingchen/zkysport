<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    protected $table = 'sub_districts';

    protected $fillable = ['name'];

    public function housingEstates()
    {
        return $this->hasMany('App\HousingEstate', 'sub_district_id');
    }
}
