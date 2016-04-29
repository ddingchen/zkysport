<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpModel extends Model
{
    protected $connection = 'sqlsrv';

    public $incrementing = false;

    public $timestamps = false;

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

    public function fromDateTime($value)
    {
        return substr(parent::fromDateTime($value), 0, -3);
    }
}
