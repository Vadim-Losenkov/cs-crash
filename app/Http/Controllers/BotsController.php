<?php

namespace App\Http\Controllers;

use App\AllItem;
use App\Bet;
use App\ChatFake;
use App\Game;
use App\Inventory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class BotsController extends Controller
{
    public function fakeBets()
    {
        $game = $this->getGameInController();

        if ($game->status > 0) {
            return [
                'success' => false,
                'message' => 'game_started_or_end'
            ];
        }

        $n = $this->config->bots_bets;

        $users = User::query()->where('is_fake', 1)->inRandomOrder()->limit($n)->get();
        $members = [];

        foreach ($users as $user) {
            $bet = Bet::query()->where([['user_id', $user->id], ['game_id', $game->id], ['is_win', 0]])->first();

            if ($bet) {
                continue;
            }

            $price = 0;
            $newItems = [];

            $itemDB = AllItem::query()->where([['price', '>=', $this->config->bots_min], ['price', '<=', $this->config->bots_max]])->inRandomOrder()->first();

            if (!$itemDB) {
                continue;
            }

            $inv = Inventory::query()->create([
                'user_id' => $user->id,
                'item_id' => $itemDB->id
            ]);

            $item = Inventory::query()->with(['item'])->where('user_id', $user->id)->find($inv->id);

            if (!$item) {
                continue;
            }

            $newItems[] = $item;
            $price += $item->item->price;

            $item->delete();

            $autoWithdraw = round($this->rand_float(1.01, 3.00), 2);

            Bet::query()->create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'items' => json_encode($newItems),
                'skins' => count($newItems),
                'bank' => $price,
                'auto_withdraw' => $autoWithdraw,
                'is_fake' => 1
            ]);

            $game->update([
                'bank' => $game->bank + $price,
                'skins' => $game->skins + count($newItems),
                'members' => count($game->users())
            ]);

            $members[] = [
                'autoWithdraw' => $autoWithdraw,
                'apiToken' => $user->api_token
            ];
        }

        return [
            'success' => true,
            'bets' => $this->getBets(),
            'game' => $game,
            'members' => $members
        ];
    }

    public function getLoadFakeMessages()
    {
        return $this->config->bots_chat;
    }

    public function sendFakeMessage()
    {
        $user = User::query()->where('is_fake', 1)->inRandomOrder()->first();

        if (!$user) return;

        $message = ChatFake::query()->inRandomOrder()->first();

        if (!$message) return;

        $returnValue = [
            'id' => Str::random(16),
            'user' => [
                'steamid' => $user->steamid,
                'avatar' => $user->avatar,
                'username' => htmlspecialchars($user->username),
                'is_admin' => $user->is_admin,
                'is_moderator' => $user->is_moderator,
                'is_ban_chat' => $user->is_ban_chat
            ],
            'message' => htmlspecialchars($message->text),
            'time' => Carbon::now()->format('H:i')
        ];

        Redis::rpush('chat', json_encode($returnValue));
        Redis::publish('newMessage', json_encode($returnValue));

        return;
    }

    private function getBets()
    {
        $game = $this->getGameInController();
        $bets = Bet::query()->with(['user', 'winItem'])->where('game_id', $game->id)->orderBy('bank', 'DESC')->get();
        $returnArray = [];

        foreach ($bets as $bet) {
            $items = [];

            foreach (json_decode($bet->items) as $item) {
                $exterior = '';

                if ($item->item->exterior) {
                    $exterior = ' (' . $item->item->exterior . ')';
                }

                $items[] = [
                    'market_hash_name' => $item->item->market_hash_name . $exterior,
                    'image' => $item->item->image,
                    'price' => $item->item->price
                ];
            }

            usort($items, function ($a, $b) {
                return ($b['price'] < $a['price']) ? -1 : 1;
            });

            $returnArray[] = [
                'user' => [
                    'steamid' => $bet->user->steamid,
                    'avatar' => $bet->user->avatar,
                    'username' => $bet->user->username
                ],
                'items' => array_slice($items, 0, 3),
                'itemsMore' => count(json_decode($bet->items)) - 3,
                'bank' => $bet->bank,
                'multiplier' => $bet->multiplier,
                'is_win' => $bet->is_win,
                'win' => $bet->win,
                'winItem' => $bet->winItem
            ];
        }

        return $returnArray;
    }

    private function getColorRarity($rarity)
    {
        if ($rarity === 'Contraband') {
            return 'yellow';
        } else if ($rarity === 'Covert' || $rarity === 'Extraordinary') {
            return 'red';
        } else if ($rarity === 'Classified') {
            return 'pink';
        } else if ($rarity === 'Restricted') {
            return 'purple';
        } else if ($rarity === 'Mil-Spec Grade') {
            return 'blue';
        } else {
            return 'light-blue';
        }
    }

    private function getGameInController()
    {
        return Game::query()->orderBy('id', 'DESC')->first();
    }

    private function rand_float($st_num = 0, $end_num = 1, $mul = 1000000)
    {
        if ($st_num > $end_num) return false;
        return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
    }
}
