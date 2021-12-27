<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Payment;
use App\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WithdrawController extends Controller
{
    public function send(Request $r)
    {
        $items = $r->get('items');
        $user = $r->user();

        if ($user->is_ban_withdraw) {
            return [
                'success' => false,
                'message' => 'blocked'
            ];
        }

        if (empty($user->trade_link)) {
            return [
                'success' => false,
                'message' => 'need_url'
            ];
        }

        if ($this->config->payment_to_withdraw && !Payment::query()->where([['status', 1], ['user_id', $user->id]])->first()) {
            return [
                'success' => false,
                'message' => 'one_withdraw'
            ];
        }

        if (count($items) > 1) {
            return [
                'success' => false,
                'message' => 'withdraw_min'
            ];
        }

        if (Cache::has('withdraw_user_' . $user->id)) {
            return [
                'success' => false,
                'message' => 'wait_withdraw'
            ];
        }
        Cache::put('withdraw_user_' . $user->id, 1, 60);

        $newItems = [];
        $price = 0;

        foreach ($items as $id => $i) {
            $item = Inventory::query()->with(['item'])->where('user_id', $user->id)->find($id);

            if (!$item) {
                Cache::pull('withdraw_user_' . $user->id);
                return [
                    'success' => false,
                    'message' => 'item_not_found'
                ];
            }

            $newItems[] = $item->item;
            $price += $item->item->price;
        }

        $paymentSum = Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum');
        $withdrawSum = 0;
        $withdraws = Withdraw::query()->with(['item'])->where([['user_id', $user->id], ['status', 1]])->get();

        foreach ($withdraws as $withdraw) {
            $withdrawSum += $withdraw->item->price;
        }

        $sum = round(round($paymentSum * $this->config->min_withdraw, 2) - $withdrawSum, 2);

        if ($sum <= 0) {
            $sum = 0.00;
        }

        if ($price > $sum) {
            Cache::pull('withdraw_user_' . $user->id);
            return [
                'success' => false,
                'message' => 'max_withdraw',
                'sum' => $sum
            ];
        }

        foreach ($items as $id => $i) {
            Inventory::query()->with(['item'])->find($id)->delete();
        }

        $itemsWithdraw = [];

        foreach ($newItems as $item) {
            $market_hash_name = $item->market_hash_name;

            if ($item->exterior) {
                $market_hash_name .= ' (' . $item->exterior . ')';
            }

            $site = 'https://market.csgo.com';

            if (intval($item->appId) === 570) {
                $site = 'https://market.dota2.net';
            }

            try {
                $url = json_decode(file_get_contents($site. '/api/v2/search-item-by-hash-name?key=' . $this->config->market_api_key . '&hash_name=' . $market_hash_name), true);
            } catch (\Exception $exception) {
                $this->returnItems($user, $newItems);
                Cache::pull('withdraw_user_' . $user->id);
                return [
                    'success' => false,
                    'item' => $market_hash_name,
                    'message' => 'withdraw_not_found'
                ];
            }

            if (!$url['success'] || !isset($url['data'][0])) {
                $this->returnItems($user, $newItems);
                Cache::pull('withdraw_user_' . $user->id);
                return [
                    'success' => false,
                    'item' => $market_hash_name,
                    'message' => 'withdraw_not_found'
                ];
            }

            $itemMarket = $url['data'][0];

            if (round(round(intval($itemMarket['price'] / 100) / $this->config->dollar, 2) / floatval($item->price), 2) > $this->config->coef_price) {
                $this->returnItems($user, $newItems);
                Cache::pull('withdraw_user_' . $user->id);
                return [
                    'success' => false,
                    'item' => $market_hash_name,
                    'message' => 'withdraw_not_found'
                ];
            }

            $itemMarket['all_items_id'] = $item->id;
            $itemMarket['appId'] = $item->appId;
            $itemMarket['item'] = $item;

            $itemsWithdraw[] = $itemMarket;
        }

        $token = $this->_parseToken($user->trade_link);
        $partner = $this->_parsePartner($user->trade_link);

        foreach ($itemsWithdraw as $item) {
            $custom_id = \Illuminate\Support\Str::random(50);

            $site = 'https://market.csgo.com';

            if (intval($item['appId']) === 570) {
                $site = 'https://market.dota2.net';
            }

            try {
                $url = json_decode(file_get_contents($site . '/api/v2/buy-for?key=' . $this->config->market_api_key . '&hash_name=' . $item['market_hash_name'] . '&price=' . $item['price'] . '&partner=' . $partner . '&token=' . $token . '&custom_id=' . $custom_id), true);
            } catch (\Exception $exception) {
                $this->returnItems($user, $newItems);
                Cache::pull('withdraw_user_' . $user->id);
                return [
                    'success' => false,
                    'item' => $item['market_hash_name'],
                    'message' => 'withdraw_not_found'
                ];
            }

            if (!$url['success']) {
                $this->returnItems($user, $newItems);
                Cache::pull('withdraw_user_' . $user->id);
                return [
                    'success' => false,
                    'item' => $item['market_hash_name'],
                    'message' => 'withdraw_not_found'
                ];
            } else {
                Withdraw::query()->create([
                    'user_id' => $user->id,
                    'item_id' => $item['all_items_id'],
                    'market_id' => $url['id'],
                    'custom_id' => $custom_id,
                    'appId' => $item['appId'],
                    'status' => 0
                ]);

                Cache::pull('withdraw_user_' . $user->id);
                return [
                    'success' => true,
                    'items' => $item['market_hash_name'],
                    'message' => 'all_items_withdraw'
                ];
            }
        }
    }

    public function getWithdraws()
    {
        $withdraws = Withdraw::query()->where('status', 0)->orderBy('id', 'DESC')->get();

        foreach ($withdraws as $withdraw) {
            if (!$withdraw->custom_id || !$withdraw->market_id) continue;

            $url = json_decode(file_get_contents('https://market.csgo.com/api/v2/get-buy-info-by-custom-id?key=' . $this->config->market_api_key . '&custom_id=' . $withdraw->custom_id), true);

            if ($url['success']) {
                $data = $url['data'];

                if ($data['stage'] === "2") {
                    $withdraw->update([
                        'status' => 1
                    ]);
                }

                if ($data['stage'] === "5") {
                    $withdraw->update([
                        'status' => 2
                    ]);

                    Inventory::query()->create([
                        'user_id' => $withdraw->user_id,
                        'item_id' => $withdraw->item_id
                    ]);
                }
            }
        }
    }

    public function getSettings()
    {
        return [
            'username' => $this->config->bot_username,
            'password' => $this->config->bot_password,
            'shared_secret' => $this->config->bot_shared_secret,
            'identity_secret' => $this->config->bot_identity_secret,
            'steamid' => $this->config->bot_steamid,
            'apiKey' => $this->config->market_api_key,
            'name_site' => $this->config->site_name
        ];
    }

    public function setStatusBot(Request $r)
    {
        $withdraw = Withdraw::query()->where([['user_id', $r->get('user')], ['item_id', $r->get('item')], ['status', 0]])->orderBy('id', 'desc')->first();

        if ($withdraw) {
            if ($r->get('status') === 2) {
                Inventory::query()->create([
                    'user_id' => $r->get('user'),
                    'item_id' => $r->get('item')
                ]);
            }

            $withdraw->update([
                'status' => $r->get('status')
            ]);
        }
    }

    public function getOffer(Request $r)
    {
        $market_hash_name = $r->get('market_hash_name');

        $item = AllItem::query()->where('market_hash_name', $market_hash_name)->first();

        if ($item) {
            $withdraw = Withdraw::query()->with(['user'])->where([['item_id', $item->id], ['status', 0]])->first();

            if ($withdraw) {
                return [
                    'item' => $item,
                    'user' => $withdraw->user
                ];
            }
        }

        return null;
    }

    private function returnItems($user, $items)
    {
        foreach ($items as $item) {
            Inventory::query()->create([
                'user_id' => $user->id,
                'item_id' => $item['id']
            ]);
        }
    }

    private function _parseToken($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['token']) ? $query_params['token'] : false;
    }

    private function _parsePartner($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['partner']) ? $query_params['partner'] : false;
    }
}
