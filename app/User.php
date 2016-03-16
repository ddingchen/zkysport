<?php

namespace App;

use Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        $openId = session('wechat.oauth_user')->id;
        return $query->where('open_id', $openId)->firstOrFail();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

}
