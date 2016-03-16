<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailInformation extends Model
{
    protected $table = 'detail_informations';

    protected $fillable = ['realname', 'tel', 'sub_district_id', 'housing_estate_id'];

    public function subDistrict()
    {
        return $this->belongsTo('App\SubDistrict', 'sub_district_id');
    }

    public function housingEstate()
    {

        return $this->belongsTo('App\HousingEstate', 'housing_estate_id');
    }

    public function information()
    {
        return $this->belongsTo('App\Information', 'information_id');
    }
}
