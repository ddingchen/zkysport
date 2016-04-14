<?php

namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['out_trade_no', 'amount', 'user_id', 'product', 'paid', 'paid_at', 'refund', 'refund_at'];

    public function information()
    {
        return $this->hasOne('App\Information', 'payment_id');
    }

    public function chargeRecord()
    {
        return $this->hasOne('App\ChargeRecord', 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function successCallbackForWxpub()
    {
        $this->paid_at = Carbon::now();
        $this->paid = true;
        switch ($this->product) {
            case 'vip_charge':
                $chargeRecord = $this->chargeRecord;
                $this->updateCardAmount($chargeRecord->card_no, $chargeRecord->amount);
                $this->writeToPaymentHistory('charge', $chargeRecord->amount, $chargeRecord->amount, true, $chargeRecord->card_no);
                break;
            default:
                break;
        }
        $this->save();
    }

    private function updateCardAmount($cardNo, $amount)
    {
        DB::connection('mysql')->update("update hy set hy_kje=hy_kje+? where hy_bh=?", [$amount, $cardNo]);
    }

    // App\Payment::first()->writeToPaymentHistory('charge',10, 10, true, '00300199')
    public function writeToPaymentHistory($bussiness, $price, $originPrice, $isCash, $cardNo)
    {
        $now = Carbon::now();
        $idPrefix = 'ZD' . $now->format('Ymd');
        // current max id
        $maxId = DB::connection('mysql')->table('jzjl')->where('Jzjl_ywbh', 'like', $idPrefix . '%')->max('Jzjl_ywbh');
        $increase = substr($maxId, -4);
        $increase = intval($increase) + 1;
        $increase = str_pad($increase, 4, '0', STR_PAD_LEFT);
        $id = $idPrefix . $increase;
        // bussiness type
        $type = '*';
        if ($bussiness == 'book') {
            // set else
        }
        // if is charge
        $isCharge = $type == '*';
        // total amount diff
        // $price
        // consume amount
        $consumeAmount = $isCharge ? 0 : $price;
        // diff between origin and promotion price
        $promotionDiffPrice = 0;
        // origin price
        // $originPrice
        // pay method
        $payMethod = $isCash ? '现金' : '储值卡';
        // card no if pay with vip
        $payCardNoComment = $isCash ? "*" : '储值卡号:' . $cardNo;
        // status
        $status = $bussiness == 'charge' ? '充值卡充值' : '已结账';
        $statusCode = $bussiness == 'charge' ? 'cz' : 'yjz';
        // card no
        $relatedCardNo = $cardNo ?: '*';
        // name
        $realname = '*';
        if ($cardNo) {
            $realname = DB::connection('mysql')->table('hy')->where('hy_bh', $cardNo)->value('hy_xm');
        }
        // cash amount
        $cashAmount = $isCash ? $price : 0;
        // vip amount
        $vipAmount = $isCash ? 0 : $price;
        DB::connection('mysql')->table('jzjl')->insert([
            'Jzjl_ywbh' => $id,
            'Jzjl_sph' => $type,
            'jzjl_khbh' => null,
            'jzjl_rs' => $isCharge ? 0 : 1,
            'jzjl_jdsj' => $now,
            'jzjl_jsrq' => $now,
            'jzjl_jsyear' => $now->year,
            'jzjl_jsmonth' => $now->month,
            'jzjl_total' => $price,
            'Jzjl_xfje' => $consumeAmount,
            'Jzjl_ysje' => 0,
            'Jzjl_dzje' => $promotionDiffPrice,
            'Jzjl_mlje' => 0,
            'Jzjl_fjje' => 0,
            'jzjl_jsje' => $originPrice,
            'Jzjl_ssje' => $price,
            'Jzjl_jssj' => $now,
            'Jzjl_jsfs' => $payMethod,
            'Jzjl_xykh' => $payCardNoComment,
            'Jzjl_skr' => 'wxpub',
            'jzjl_zt' => $status,
            'Jzjl_jzzt' => $statusCode,
            'jzjl_hybh' => $relatedCardNo,
            'jzjl_hyxm' => $realname,
            'jzjl_bz' => null,
            'jzjl_sby' => null,
            'jzjl_dby' => null,
            'jzjl_fby' => null,
            'jzjl_xj' => $cashAmount,
            'jzjl_czk' => $vipAmount,
            'jzjl_djq' => 0,
            'jzjl_xyk' => 0,
            'jzjl_yzk' => 0,
            'jzjl_bmbh' => null,
            'jzjl_source' => null,
            'jzjl_shop' => null,
            'jzjl_sfjb' => null]);
    }
}
