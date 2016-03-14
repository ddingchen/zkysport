<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['open_id', 'nickname', 'head_image'];

    public function scopeInSession($query)
    {
        if (!session('wechat.oauth_user')) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model));
        }
        $openId = session('wechat.oauth_user')->id;
        return $query->where('open_id', $openId)->firstOrFail();
    }
}
