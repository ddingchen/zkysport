<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HousingEstate extends Model
{
    protected $table = 'housing_estates';

    protected $fillable = ['name'];
}
