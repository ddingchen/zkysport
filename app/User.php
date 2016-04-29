<?php

namespace App;

use App\MpVip;
use DB;
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

    public function getSaltAttribute()
    {
        $salt = DB::table('user_salt')->where('user_id', $this->id)->value('salt');
        if (!$salt) {
            $salt = $this->setUserSalt();
        }
        return $salt;
    }

    private function setUserSalt()
    {
        $salt = time();
        $baseQuery = DB::table('user_salt')->where('user_id', $this->id);
        $exist = $baseQuery->count() > 0;
        if ($exist) {
            $baseQuery->update(['salt' => $salt]);
        } else {
            DB::table('user_salt')->insert([
                'user_id' => $this->id,
                'salt' => $salt,
            ]);
        }
        return $salt;
    }

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

    public function cards()
    {
        return $this->hasMany('App\MpCard', 'hy_wxkh', 'open_id');
    }

    public function vips()
    {
        return MpCard::byUser($this)->get()->map(function ($card) {
            return $card->vip;
        });
    }

    public function chargeRecords()
    {
        return $this->hasMany('App\ChargeRecord', 'user_id');
    }

    public function mpChargeRecords()
    {
        $payments = collect();
        foreach ($this->cards as $card) {
            $payments->push($card->payments->filter(function ($payment) {
                return $payment->isCharge;
            }));
        }
        return $payments->flatten();
    }

    public function mpConsumeRecords()
    {
        $payments = collect();
        foreach ($this->cards as $card) {
            $payments->push($card->payments->filter(function ($payment) {
                return !$payment->isCharge;
            }));
        }
        return $payments->flatten();
    }

    public function bookingOrders()
    {
        return $this->hasMany('App\BookingOrder', 'user_id');
    }

    public function finishedBookingOrders()
    {
        return $this->bookingOrders->filter(function ($item) {
            return $item->isFinished;
        });
    }

    public function unfinishedBookingOrders()
    {
        return $this->bookingOrders->filter(function ($item) {
            return !$item->isFinished;
        });
    }

    public function informations()
    {
        return $this->hasMany('App\Information', 'user_id');
    }

    public function joinedExpiredActivities()
    {
        return $this->informations->filter(function ($info) {
            return $info->payment->paid && $info->activity->expired;
        });
    }

    public function joinedActiveActivities()
    {
        return $this->informations->filter(function ($info) {
            return $info->payment->paid && !$info->activity->expired;
        });
    }

    public function alreadyGotTheVip($vipId)
    {
        return $this->vips()->lists('id')->contains($vipId);
    }

    public function activeVip()
    {
        $vipId = DB::table('user_active_vip')->where('user_id', $this->id)->value('vip_id');
        try {
            $vip = MpVip::findById($vipId);
        } catch (ModelNotFoundException $e) {
            $vip = $this->setDefaultVip();
        }
        if (!$vip) {
            return null;
        }
        $cardExist = MpCard::byUser($this)->byVip($vip)->count() > 0;
        if (!$cardExist) {
            $vip = $this->setDefaultVip();
        }
        return $vip;
    }

    public function setDefaultVip()
    {
        $card = MpCard::byUser($this)->first();
        if (!$card) {
            return null;
        }
        $vip = $card->vip;
        $recordExist = DB::table('user_active_vip')->where('user_id', $this->id)->count() > 0;
        if ($recordExist) {
            DB::table('user_active_vip')->where('user_id', $this->id)->update(['vip_id' => $vip->id]);
        } else {
            DB::table('user_active_vip')->insert([
                'user_id' => $this->id,
                'vip_id' => $vip->id,
            ]);
        }
        return $vip;
    }

}
