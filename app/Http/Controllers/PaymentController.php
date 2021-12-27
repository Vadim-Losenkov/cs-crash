<?php

namespace App\Http\Controllers;

use App\AllItem;
use App\Inventory;
use App\Payment;
use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function sumCreate(Request $r)
    {
        $sum = $r->get('sum');

        if ($sum < $this->config->freekassa_sum) {
            return [
                'success' => false,
                'message' => 'min_payment',
                'sum' => $this->config->freekassa_sum
            ];
        }

        $payment = Payment::query()->create([
            'user_id' => $r->user()->id,
            'sum' => $sum
        ]);

        $sign = md5($this->config->freekassa_id . ':' . round($sum * $this->config->dollar, 2) . ':' . $this->config->freekassa_secret_1 . ':' . $payment->id);

        return [
            'success' => true,
            'url' => 'http://www.free-kassa.ru/merchant/cash.php?m=' . $this->config->freekassa_id . '&oa=' . round($sum * $this->config->dollar, 2) . '&o=' . $payment->id . '&s=' . $sign
        ];
    }

    public function sumHandle(Request $r)
    {
        $payment = Payment::query()->find($r['MERCHANT_ORDER_ID']);
        if (!$payment || $payment->status) die('Not found payment');

        $sign = md5($this->config->freekassa_id . ':' . round($payment->sum * $this->config->dollar, 2) . ':' . $this->config->freekassa_secret_2 . ':' . $payment->id);
        if ($sign !== $r['SIGN']) die('Invalid sign');

        $user = User::query()->find($payment->user_id);
        if (!$user) die('Invalid user');

        if (!empty($user->referral_use)) {
            $referralUser = User::query()->where('referral_code', $user->referral_use)->first();

            if ($referralUser) {
                $percent = round($payment->sum * ($this->config->percent_referral / 100), 2);
                $referralUser->increment('balance', $percent);
                $referralUser->increment('referral_sum', $percent);
            }
        }

        $payment->update([
            'status' => 1
        ]);

        $user->increment('balance', $payment->sum);

        die('Success');

    }

    public function skinsCreate(Request $r)
    {
        return [
            'success' => true,
            'url' => $this->config->bot_trade_url
        ];
    }

    public function depositSkins(Request $r)
    {
        $items = $r->get('items');
        $steamid = $r->get('steamid');

        $user = User::query()->where('steamid', $steamid)->first();

        if (count($items) <= 0) {
            return [
                'success' => false,
                'user_id' => $user->id,
                'message' => 'your_not_deposit',
                'refreshInventory' => false
            ];
        }

        if ($user) {
            foreach ($items as $item) {
                $db = AllItem::query()->where('market_hash_name', $item['market_hash_name'])->first();

                if (!$db) {
                    return [
                        'success' => false,
                        'user_id' => $user->id,
                        'message' => 'error_price',
                        'refreshInventory' => false
                    ];
                }

                Inventory::query()->create([
                    'user_id' => $user->id,
                    'item_id' => $db->id
                ]);
            }

            return [
                'success' => true,
                'user_id' => $user->id,
                'message' => 'success',
                'refreshInventory' => true
            ];
        }
    }
}
