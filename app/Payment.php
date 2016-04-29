<?php

namespace App;

use App\AreaManager;
use App\MpCard;
use App\MpPayment;
use App\MpVip;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['out_trade_no', 'amount', 'balance', 'user_id', 'vip_id', 'product', 'paid', 'paid_at', 'refund', 'refund_at'];

    public function getTitleAttribute()
    {
        $title = '';
        switch ($this->product) {
            case 'activity':
                $title = '活动报名';
                break;
            case 'vip_buy':
                $title = 'VIP购买';
                break;
            case 'vip_charge':
                $title = 'VIP充值';
                break;
            default:
                $title = '';
                break;
        }
        return $title;
    }

    public function getDescriptionAttribute()
    {
        $desc = "";
        switch ($this->product) {
            case 'activity':
                $desc = $this->activity->name;
                break;
            case 'vip_buy':
                $chargeRecord = $this->chargeRecord;
                $card = MpCard::findByNo($chargeRecord->card_no);
                $desc = '购买价值¥' . $chargeAmount . '元的' . $card->vip->name;
                break;
            case 'vip_charge':
                $chargeRecord = $this->chargeRecord;
                $desc = '充值¥' . $chargeRecord->amount . '至卡号为' . $chargeRecord->card_no . '的帐户';
                break;
            default:
                $desc = '';
                break;
        }
        return $desc;
    }

    public function information()
    {
        return $this->hasOne('App\Information', 'payment_id');
    }

    public function chargeRecord()
    {
        return $this->hasOne('App\ChargeRecord', 'payment_id');
    }

    public function bookingOrder()
    {
        return $this->hasOne('App\BookingOrder', 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopePrepare($query, User $user, $amount, $product, $payMethod = 'wxpub')
    {
        $tradeNo = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
        $balance = null;
        if ($payMethod != 'wxpub') {
            $card = MpCard::byUser(User::inSession())->byVip(MpVip::findById($payMethod))->first();
            $balance = $card ? $card->amount - $amount : null;
        }
        $payment = Payment::create([
            'amount' => $amount,
            'balance' => $balance,
            'purchase_at' => time(),
            'out_trade_no' => $tradeNo,
            'product' => $product,
            'user_id' => $user->id,
            'vip_id' => $payMethod == 'wxpub' ? null : $payMethod,
        ]);
        return $payment;
    }

    public function successCallbackForWxpub()
    {
        $this->paid_at = Carbon::now();
        $this->paid = true;
        $isCash = $this->vip_id == null;
        $user = User::find($this->user_id);
        $cardNo = $isCash ? null : MpCard::byUser($user)->byVip(MpVip::findById($this->vip_id))->get()->first()->no;
        \Log::debug($cardNo);
        switch ($this->product) {
            case 'book':
                $order = $this->bookingOrder;
                $order->expired_at = null;
                $order->save();
                AreaManager::findByOrder($order)->delete();
                $this->writeToPaymentHistory('book', $this->amount, $order->origin_amount, $isCash, $cardNo, $order->number_of_people);
                break;
            case 'vip_charge':
                $chargeRecord = $this->chargeRecord;
                $this->updateCardAmount($chargeRecord->card_no, $chargeRecord->amount);
                $this->writeToPaymentHistory('charge', $chargeRecord->amount, $chargeRecord->amount, true, $chargeRecord->card_no);
                break;
            case 'vip_buy':
                $chargeRecord = $this->chargeRecord;
                $cardNo = $this->createCard($chargeRecord->vip_id, $chargeRecord->name, $chargeRecord->tel);
                $this->updateCardAmount($cardNo, $chargeRecord->amount);
                $this->writeToPaymentHistory('charge', $chargeRecord->amount, $chargeRecord->amount, true, $cardNo);
                break;
            default:
                break;
        }
        $this->save();
    }

    public function updateCardAmount($cardNo, $amount)
    {
        $card = MpCard::findByNo($cardNo);
        $card->amount += $amount;
        $card->save();
    }

    // App\Payment::first()->writeToPaymentHistory('charge',10, 10, true, '00300199')
    public function writeToPaymentHistory($bussiness, $price, $originPrice, $isCash = true, $cardNo = null, $numberOfPeople = 0)
    {
        $now = Carbon::now();
        $idPrefix = 'WX' . $now->format('Ymd');
        // current max id
        $maxId = MpPayment::where('Jzjl_ywbh', 'like', $idPrefix . '%')->max('Jzjl_ywbh');
        $increase = substr($maxId, -4);
        $increase = intval($increase) + 1;
        $increase = str_pad($increase, 4, '0', STR_PAD_LEFT);
        $id = $idPrefix . $increase;
        // bussiness type. "*":charge;"B1":book
        $type = '*';
        if ($bussiness == 'book') {
            $type = $this->bookingOrder->areasDesc;
        }
        // total amount diff
        // $price
        // consume amount
        $consumeAmount = $price;
        // diff between origin and promotion price
        $promotionDiffPrice = $originPrice - $price;
        // pay method
        $payMethod = $isCash ? '现金' : '储值卡';
        // status
        $status = $bussiness == 'charge' ? '充值卡充值' : '已结账';
        $statusCode = $bussiness == 'charge' ? 'cz' : 'yjz';
        // card no
        $relatedCardNo = $cardNo ?: '*';
        // name
        $realname = '*';
        if ($cardNo) {
            $realname = MpCard::where('hy_bh', $cardNo)->value('hy_xm');
        }
        // cash amount
        $cashAmount = $isCash ? $price : 0;
        // vip amount
        $vipAmount = $isCash ? 0 : $price;

        $attribute = [
            'Jzjl_ywbh' => $id,
            'Jzjl_sph' => $type,
            'jzjl_rs' => $numberOfPeople,
            'jzjl_jdsj' => $now,
            'jzjl_jsrq' => $now,
            'jzjl_jsyear' => $now->year,
            'jzjl_jsmonth' => $now->month,
            'jzjl_total' => $originPrice,
            'Jzjl_xfje' => $consumeAmount,
            'Jzjl_dzje' => $promotionDiffPrice,
            'jzjl_jsje' => $consumeAmount,
            'Jzjl_ssje' => $cashAmount,
            'Jzjl_jssj' => $now,
            'Jzjl_jsfs' => $payMethod,
            'jzjl_zt' => $status,
            'Jzjl_jzzt' => $statusCode,
            'jzjl_hybh' => $relatedCardNo,
            'jzjl_hyxm' => $realname,
            'jzjl_xj' => $cashAmount,
            'jzjl_czk' => $vipAmount,
        ];
        MpPayment::create($attribute);
    }

    private function createCard($vipId, $name, $tel)
    {
        $now = Carbon::now();
        // card no
        $cardNoPrefix = '01';
        $mpVipCode = $this->translateToMpVipCode($vipId);
        $maxCardNo = MpCard::where('hy_bh', 'like', $cardNoPrefix . $mpVipCode . '%')->value('hy_bh');
        $increase = str_pad(intval(substr($maxCardNo, -5)) + 1, 5, '0', STR_PAD_LEFT);
        $newCardNo = $cardNoPrefix . $mpVipCode . $increase;
        // pingyin
        $namePy = $this->getHeadCharOfPY($name);
        // vip type id
        $mpId = MpVip::findById($vipId)->mpId;
        // open id
        $openId = User::inSession()->openId;
        MpCard::create([
            'hy_xh' => 'wxpub' . $now->format('YmdHis') . $now->micro,
            'Hy_bh' => $newCardNo,
            'Hy_xm' => $name,
            'hy_jp' => $namePy,
            'Hy_dh' => $tel,
            'Hy_sr' => $now,
            'Hy_hydj' => $mpId,
            'Hy_djrq' => $now,
            'hy_dby' => $now,
            'hy_usedate' => $now,
            'hy_wxkh' => $openId,
        ]);
        return $newCardNo;
    }

    private function translateToMpVipCode($vipId)
    {
        $mpVipCode = 0;
        switch ($vipId) {
            case '1':
                $mpVipCode = 0;
                break;
            case '2':
                $mpVipCode = 1;
                break;
            case '3':
                $mpVipCode = 3;
                break;
            default:
                break;
        }
        return $mpVipCode;
    }

    private function getHeadCharOfPY($str)
    {
        $asc = ord(substr($str, 0, 1));
        if ($asc < 160) {
            if ($asc >= 48 && $asc <= 57) {
                return '1';
            } elseif ($asc >= 65 && $asc <= 90) {
                return chr($asc);
            } elseif ($asc >= 97 && $asc <= 122) {
                return chr($asc - 32);
            } else {
                return '~';
            }
        } else {
            $asc = $asc * 1000 + ord(substr($str, 1, 1));
            if ($asc >= 176161 && $asc < 176197) {
                return 'A';
            } elseif ($asc >= 176197 && $asc < 178193) {
                return 'B';
            } elseif ($asc >= 178193 && $asc < 180238) {
                return 'C';
            } elseif ($asc >= 180238 && $asc < 182234) {
                return 'D';
            } elseif ($asc >= 182234 && $asc < 183162) {
                return 'E';
            } elseif ($asc >= 183162 && $asc < 184193) {
                return 'F';
            } elseif ($asc >= 184193 && $asc < 185254) {
                return 'G';
            } elseif ($asc >= 185254 && $asc < 187247) {
                return 'H';
            } elseif ($asc >= 187247 && $asc < 191166) {
                return 'J';
            } elseif ($asc >= 191166 && $asc < 192172) {
                return 'K';
            } elseif ($asc >= 192172 && $asc < 194232) {
                return 'L';
            } elseif ($asc >= 194232 && $asc < 196195) {
                return 'M';
            } elseif ($asc >= 196195 && $asc < 197182) {
                return 'N';
            } elseif ($asc >= 197182 && $asc < 197190) {
                return 'O';
            } elseif ($asc >= 197190 && $asc < 198218) {
                return 'P';
            } elseif ($asc >= 198218 && $asc < 200187) {
                return 'Q';
            } elseif ($asc >= 200187 && $asc < 200246) {
                return 'R';
            } elseif ($asc >= 200246 && $asc < 203250) {
                return 'S';
            } elseif ($asc >= 203250 && $asc < 205218) {
                return 'T';
            } elseif ($asc >= 205218 && $asc < 206244) {
                return 'W';
            } elseif ($asc >= 206244 && $asc < 209185) {
                return 'X';
            } elseif ($asc >= 209185 && $asc < 212209) {
                return 'Y';
            } elseif ($asc >= 212209) {
                return 'Z';
            } else {
                return '~';
            }
        }
    }
}
