<?php

namespace App;

use App\MpModel;
use App\User;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MpCard extends MpModel
{

    protected $table = 'hy';

    protected $fillable = ['hy_xh', 'Hy_bh', 'Hy_xm', 'hy_jp', 'Hy_sr', 'Hy_hydj', 'Hy_djrq', 'hy_dby', 'hy_usedate', 'hy_wxkh'];

    protected $primaryKey = 'Hy_bh';

    protected $dates = ['Hy_sr', 'Hy_djrq', 'hy_dby', 'hy_usedate'];

    protected $attributes = [
        // 'hy_xh' => 'wxpub' . $now->format('YmdHis') . $now->micro,
        // 'Hy_bh' => $newCardNo,
        // 'Hy_xm' => '未设定',
        // 'hy_jp' => 'WSD',
        'Hy_xb' => '男',
        // 'Hy_sr' => time(),
        'Hy_dh' => '',
        // 'Hy_hydj' => $typeId,
        'Hy_dqjf' => 0,
        // 'Hy_djrq' => time(),
        'Hy_dqzt' => '可用',
        'hy_xfzje' => 0,
        'hy_amount' => null,
        'hy_bz' => '无',
        'hy_klx' => '储值卡',
        'hy_kje' => 0,
        'hy_sby' => 'N',
        // 'hy_dby' => time(),
        'hy_fby' => 0,
        'hy_kmm' => null,
        'hy_autjf' => 'N',
        'hy_both' => 'Y',
        // 'hy_usedate' => time(),
        'hy_area' => 'A',
        'hy_klxbh' => 'czk',
        'hy_idcard' => '*',
        'hy_same' => null,
        'hy_czy' => 'wxpub',
        'hy_hjgw' => '活动',
        // 'hy_wxkh' => $openId,
        'hy_djmc' => null,
        'hy_dzbl' => null,
        'hy_Shop' => null,
    ];

    public function getNoAttribute()
    {
        return $this->Hy_bh;
    }

    public function getTelAttribute()
    {
        return $this->Hy_dh;
    }

    public function getAmountAttribute()
    {
        return $this->hy_kje;
    }

    public function setAmountAttribute($value)
    {
        $this->hy_kje = $value;
    }

    public function getOpenIdAttribute()
    {
        return $this->hy_wxkh;
    }

    public function setOpenIdAttribute($value)
    {
        $this->hy_wxkh = $value;
    }

    public function isActive()
    {
        $userId = $this->user->id;
        $vipId = $this->vip->id;
        $active = DB::table('user_active_vip')
            ->where('user_id', $userId)
            ->where('vip_id', $vipId)
            ->count() > 0;
        return $active;
    }

    public function active()
    {
        $userId = User::inSession()->id;
        $vipId = $this->vip->id;
        $recordExist = DB::table('user_active_vip')->where('user_id', $userId)->count() > 0;
        if ($recordExist) {
            DB::table('user_active_vip')->where('user_id', $userId)->update(['vip_id' => $vipId]);
        } else {
            DB::table('user_active_vip')->insert([
                'user_id' => $userId,
                'vip_id' => $vipId,
            ]);
        }
    }

    public function scopeByUser($query, User $user)
    {
        return $query->where('hy_wxkh', $user->openId);
    }

    public function scopeByVip($query, MpVip $vip)
    {
        return $query->where('Hy_hydj', $vip->mpid);
    }

    public function scopeFindByNo($query, $cardNo)
    {
        $model = $query->where('Hy_bh', $cardNo)->first();
        if (!$model) {
            throw (new ModelNotFoundException)->setModel(get_class($this->model));
        }
        return $model;
    }

    public function hadByUser(User $user)
    {
        return $this->hy_wxkh == $user->openId;
    }

    public function vip()
    {
        return $this->belongsTo('App\MpVip', 'Hy_hydj', 'Hydj_bh');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'hy_wxkh', 'open_id');
    }

    public function getIsBoundAttribute()
    {
        if (!$this->openId) {
            return false;
        }
        $user = $this->user;
        if ($user) {
            return true;
        }
        return false;
    }

    public function payments()
    {
        return $this->hasMany('App\MpPayment', 'jzjl_hybh', 'Hy_bh');
    }

}
