<?php

namespace App\Http\Controllers;

use App\AllItem;
use App\Bet;
use App\Inventory;
use App\Promocode;
use App\PromocodeUse;
use App\User;
use App\Withdraw;
use Illuminate\Http\Request;
use function Sodium\increment;

class UserController extends Controller
{
    public function getInfo(Request $r)
    {
        if ($r->user()->is_ban) {
            return [
                'banned' => true
            ];
        } else {
            return $r->user();
        }
    }

    public function getInventory(Request $r)
    {
        return Inventory::query()
            ->join('all_items', 'inventories.item_id', '=', 'all_items.id')
            ->orderBy('all_items.price', 'DESC')
            ->where([['user_id', $r->user()->id], ['appId', $r->get('appId')]])
            ->select('*', 'inventories.id as myId')
            ->get();
    }

    public function buy(Request $r)
    {
        $ids = $r->get('ids');
        $myIds = $r->get('myIds');
        $user = $r->user();

        if (count($ids) <= 0) {
            return [
                'success' => false,
                'message' => 'items_not_selected'
            ];
        }

        $price = 0;
        $myItemsBalance = 0;

        foreach ($ids as $id => $i) {
            $item = AllItem::query()->find($id);

            if (!$item) {
                return [
                    'success' => false,
                    'message' => 'item_not_found'
                ];
            }

            $price += $item->price;
        }

        foreach ($myIds as $id => $i) {
            $item = Inventory::query()->with(['item'])->where('user_id', $user->id)->find($id);

            if (!$item) {
                return [
                    'success' => false,
                    'message' => 'item_not_found'
                ];
            }

            $myItemsBalance += $item->item->price;
        }

        $extBalance = round($price - $myItemsBalance, 2);

        if ($extBalance > 0 && round($user->balance, 2) < $extBalance) {
            return [
                'success' => false,
                'message' => 'not_balance'
            ];
        } else if ($extBalance === 0 && round($user->balance, 2) < $price) {
            return [
                'success' => false,
                'message' => 'not_balance'
            ];
        }

        foreach ($myIds as $id => $i) {
            Inventory::query()->find($id)->delete();
        }

        foreach ($ids as $id => $i) {
            Inventory::query()->create([
                'item_id' => $id,
                'user_id' => $user->id
            ]);
        }

        $user->decrement('balance', round($extBalance, 2));

        return [
            'success' => true,
            'newBalance' => round($user->balance, 2)
        ];
    }

    public function getProfile(Request $r)
    {
        $user = $r->user();

        $betsArray = [];

        foreach (Bet::query()->with(['winItem'])->where('user_id', $user->id)->limit(20)->orderBy('id', 'DESC')->get() as $bet) {
            $itemsArray = [];

            foreach (json_decode($bet->items) as $item) {
                $itemsArray[] = [
                    'image' => $item->item->image,
                    'price' => $item->item->price,
                    'market_hash_name' => $item->item->market_hash_name
                ];
            }

            usort($itemsArray, function ($a, $b) {
                return ($b['price'] < $a['price']) ? -1 : 1;
            });

            $betsArray[] = [
                'game' => [
                    'id' => $bet->game_id
                ],
                'user' => [
                    'steamid' => $user->steamid,
                    'avatar' => $user->avatar,
                    'username' => $user->username
                ],
                'items' => $itemsArray,
                'is_win' => $bet->is_win,
                'win' => $bet->win,
                'bank' => $bet->bank,
                'multiplier' => $bet->multiplier,
                'winItem' => $bet->winItem,
                'date' => $bet->created_at->format('d.m.Y H:i')
            ];
        }

        $withdrawArray = [];

        foreach (Withdraw::query()->with(['item'])->where('user_id', $user->id)->orderBy('id', 'desc')->get() as $withdraw) {
            $withdrawArray[] = [
                'status' => $withdraw->status,
                'item' => $withdraw->item
            ];
        }

        $user->referrals = User::query()->where('referral_use', $user->referral_code)->count('id');

        return [
            'user' => $user,
            'bets' => $betsArray,
            'withdraws' => $withdrawArray
        ];
    }

    public function getUser(Request $r)
    {
        $user = User::query()->where('steamid', $r->get('steamid'))->first();

        if (!$user) {
            return [
                'success' => false
            ];
        }

        $betsArray = [];

        foreach (Bet::query()->where('user_id', $user->id)->limit(20)->orderBy('id', 'DESC')->get() as $bet) {
            $itemsArray = [];

            foreach (json_decode($bet->items) as $item) {
                $itemsArray[] = [
                    'image' => $item->item->image,
                    'price' => $item->item->price,
                    'market_hash_name' => $item->item->market_hash_name
                ];
            }

            usort($itemsArray, function ($a, $b) {
                return ($b['price'] < $a['price']) ? -1 : 1;
            });

            $betsArray[] = [
                'game' => [
                    'id' => $bet->game_id
                ],
                'user' => [
                    'steamid' => $user->steamid,
                    'avatar' => $user->avatar,
                    'username' => $user->username
                ],
                'items' => $itemsArray,
                'is_win' => $bet->is_win,
                'win' => $bet->win,
                'bank' => $bet->bank,
                'multiplier' => $bet->multiplier,
                'winItem' => $bet->winItem,
                'date' => $bet->created_at->format('d.m.Y H:i')
            ];
        }

        return [
            'user' => [
                'username' => $user->username
            ],
            'bets' => $betsArray,
            'success' => true
        ];
    }

    public function saveUrl(Request $r)
    {
        $link = $r->get('trade_link');

        if ($this->_parseTradeLink($link)) {
            $r->user()->update([
                'trade_link' => $link
            ]);
            return response()->json(['type' => 'success', 'message' => 'url_save']);
        } else {
            return response()->json(['type' => 'error', 'message' => 'url_not_save']);
        }
    }

    public function usePromo(Request $r)
    {
        $promo = $r->get('promocode');

        $promocode = Promocode::query()->where('name', $promo)->first();

        if (!$promocode) {
            return [
                'type' => 'error',
                'message' => 'promo_not_found'
            ];
        }

        $useds = PromocodeUse::query()->where('promocode_id', $promocode->id)->count('id');

        if ($useds >= $promocode->activates) {
            return [
                'type' => 'error',
                'message' => 'promo_end'
            ];
        }

        $used = PromocodeUse::query()->where([['promocode_id', $promocode->id], ['user_id', $r->user()->id]])->first();

        if ($used) {
            return [
                'type' => 'error',
                'message' => 'promo_user_end'
            ];
        }

        PromocodeUse::query()->create([
            'user_id' => $r->user()->id,
            'promocode_id' => $promocode->id
        ]);

        $r->user()->increment('balance', $promocode->sum);

        return [
            'type' => 'success',
            'newBalance' => $r->user()->balance,
            'message' => 'promo_used'
        ];
    }

    private function _parseTradeLink($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['token']) ? $query_params['token'] : false;
    }
}

