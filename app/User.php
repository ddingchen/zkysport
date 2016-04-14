<?php

namespace App;

use Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'open_id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeInSession($query)
    {
        if (!session('wechat.oauth_user')) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model));
        }
        return $query->where('open_id', session('wechat.oauth_user')->id)->firstOrFail();
    }

    public function getOpenIdAttribute($value)
    {
        if (!session('wechat.oauth_user')) {
            return $value;
        }
        return session('wechat.oauth_user')->id;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function seller()
    {
        return $this->hasOne('App\Seller');
    }

}
