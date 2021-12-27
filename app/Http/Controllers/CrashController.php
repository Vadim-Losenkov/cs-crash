<?php

namespace App\Http\Controllers;

use App\AllItem;
use App\Bet;
use App\Game;
use App\Inventory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CrashController extends Controller
{
    public function newBet(Request $r)
    {
        $items = $r->get('items');
        $apiToken = $r->get('apiToken');
        $autoWithdraw = $r->get('autoWithdraw');

        if (count($items) <= 0) {
            return [
                'success' => false,
                'message' => 'select_items'
            ];
        }

        if ($autoWithdraw <= 1.00) {
            return [
                'success' => false,
                'message' => 'select_withdraw'
            ];
        }

        $game = $this->getGameInController();

        $user = User::query()->where('api_token', $apiToken)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'user_not_found'
            ];
        }

        if ($game->status > 0) {
            return [
                'success' => false,
                'message' => 'game_started_or_end'
            ];
        }

        if (count($items) > $this->config->max_items) {
            return [
                'success' => false,
                'message' => 'max_items'
            ];
        }

        $bet = Bet::query()->where([['user_id', $user->id], ['game_id', $game->id], ['is_win', 0]])->first();

        if ($bet) {
            return [
                'success' => false,
                'message' => 'user_bet'
            ];
        }

        $price = 0;
        $newItems = [];

        foreach ($items as $id => $i) {
            $item = Inventory::query()->with(['item'])->where('user_id', $user->id)->find($id);

            if (!$item) {
                return [
                    'success' => false,
                    'message' => 'item_not_found'
                ];
            }

            $newItems[] = $item;
            $price += $item->item->price;
        }

        foreach ($items as $id => $i) {
            Inventory::query()->with(['item'])->find($id)->delete();
        }

        Bet::query()->create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'items' => json_encode($newItems),
            'skins' => count($newItems),
            'bank' => $price,
            'auto_withdraw' => $autoWithdraw
        ]);

        $game->update([
            'bank' => $game->bank + $price,
            'skins' => $game->skins + count($newItems),
            'members' => count($game->users())
        ]);

        return [
            'success' => true,
            'price' => $price,
            'bets' => $this->getBets(),
            'game' => $game,
            'bet' => $this->userBet($user)
        ];
    }

    public function getGame()
    {
        $game = Game::query()->where('status', '<', Game::STATUS_END)->first();

        if ($game) {
            return [
                'game' => $game,
                'bets' => $this->getBets(),
                'history' => $this->getHistory()
            ];
        }

        Game::query()->create([
            'multiplier' => 0.00
        ]);

        $game = Game::query()->orderBy('id', 'DESC')->first();

        return [
            'game' => $game,
            'bets' => $this->getBets(),
            'history' => $this->getHistory()
        ];
    }

    public function setStatus(Request $r)
    {
        $status = $r->get('status');
        $game = $this->getGameInController();

        $game->update([
            'status' => $status
        ]);

        if ($status === 1) {
            $profit = Game::query()->sum('profit');

            $ext = $profit - $game->bank;

            if ($ext < $this->config->profit) {
                $multiplier = 1.00;
            } else {
                if ($game->bank > 0) {
                    $otd = $ext - $this->config->profit;

                    if ($otd <= 0) {
                        $multiplier = 1.00;
                    } else {
                        $max = round(($otd) / $game->bank, 2);

                        if ($max < 1) {
                            $max += 1;
                        }

                        if ($max >= 100.00) {
                            $max = 99.99;
                        }

                        $multiplier = round($this->rand_float(1.00, $max), 2);
                    }
                } else {
                    $multiplier = round($this->rand_float(1.00, 2.50), 2);
                }
            }

            $game->update([
                'multiplier' => $multiplier
            ]);

            return $multiplier;
        }
    }

    public function crashBets()
    {
        $game = $this->getGameInController();
        Bet::query()->where([['game_id', $game->id], ['is_win', 0]])->update([
            'is_win' => 2,
            'multiplier' => $game->multiplier
        ]);

        $bets = $this->getBets();

        $bank = Bet::query()->where([['game_id', $game->id], ['is_fake', 0]])->sum('bank');
        $win = Bet::query()->where([['game_id', $game->id], ['is_fake', 0]])->sum('win');

        $game->update([
            'status' => 2,
            'profit' => $bank - $win
        ]);

        return $bets;
    }

    public function myBet(Request $r)
    {
        return [
            'lastBet' => $this->lastBet($r->user()),
            'userBet' => $this->userBet($r->user())
        ];
    }

    public function take(Request $r)
    {
        $apiToken = $r->get('apiToken');
        $multiplier = $r->get('multiplier');
        $game = $this->getGameInController();

        $user = User::query()->where('api_token', $apiToken)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'user_not_found'
            ];
        }

        if (Redis::get('withdraw_' . $game->id . '_' . $user->id)) {
            return [
                'success' => false,
                'message' => 'user_withdraw'
            ];
        }

        Redis::set('withdraw_' . $game->id . '_' . $user->id, 1);

        $bet = Bet::query()->where([['game_id', $game->id], ['user_id', $user->id], ['is_win', 0]])->first();

        if (!$bet) {
            return [
                'success' => false,
                'message' => 'user_not_bet'
            ];
        }

        $items = json_decode($bet->items, true);

        $appId = $items[0]['item']['appId'];

        $win = round($bet->bank * $multiplier, 2);
        $winItem = AllItem::query()->where([['price', '<=', $win], ['appId', $appId]])->orderBy('price', 'DESC')->first();
        $extBalance = $win - $winItem->price;

        $bet->update([
            'is_win' => 1,
            'win' => $win,
            'multiplier' => $multiplier,
            'win_item_id' => $winItem->id
        ]);

        Inventory::query()->create([
            'user_id' => $user->id,
            'item_id' => $winItem->id
        ]);

        if ($extBalance > 0) {
            $user->increment('balance', $extBalance);
        }

        return [
            'success' => true,
            'item' => $this->lastBet($user),
            'win' => $win,
            'bets' => $this->getBets(),
            'newBalance' => $user->balance
        ];
    }

    public function autoTake(Request $r)
    {
        $members = $r->get('members');
        $multiplier = $r->get('multiplier');
        $autoWithdraws = $r->get('autoWithdraws');
        $game = $this->getGameInController();

        $returnArray = [];

        foreach ($members as $member) {
            $apiToken = $member['token'];

            if (in_array($apiToken, $autoWithdraws)) {
                continue;
            }

            $user = User::query()->where('api_token', $apiToken)->first();

            if (!$user) {
                continue;
            }

            if (Redis::get('withdraw_' . $game->id . '_' . $user->id)) {
                continue;
            }

            Redis::set('withdraw_' . $game->id . '_' . $user->id, 1);

            $bet = Bet::query()->where([['game_id', $game->id], ['user_id', $user->id], ['is_win', 0]])->first();

            if (!$bet) {
                continue;
            }

            $items = json_decode($bet->items, true);

            $appId = $items[0]['item']['appId'];

            $win = round($bet->bank * $multiplier, 2);
            $winItem = AllItem::query()->where([['price', '<=', $win], ['appId', $appId]])->orderBy('price', 'DESC')->first();
            $extBalance = $win - $winItem->price;

            $bet->update([
                'is_win' => 1,
                'win' => $win,
                'multiplier' => $multiplier,
                'win_item_id' => $winItem->id
            ]);

            Inventory::query()->create([
                'user_id' => $user->id,
                'item_id' => $winItem->id
            ]);

            if ($extBalance > 0) {
                $user->increment('balance', $extBalance);
            }

            $returnArray[] = [
                'item' => $this->lastBet($user),
                'win' => $win,
                'newBalance' => $user->balance,
                'user_id' => $user->id
            ];
        }

        return [
            'members' => $returnArray,
            'bets' => $this->getBets()
        ];
    }

    public function getGameHistory()
    {
        return $this->getHistory();
    }

    public function getGameBets(Request $r)
    {
        $game = Game::query()->where('status', 2)->find($r->get('id'));

        if (!$game) {
            return [
                'success' => false
            ];
        }

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

        return [
            'success' => true,
            'game' => [
                'id' => $game->id,
                'bank' => $game->bank,
                'members' => $game->members,
                'skins' => $game->skins,
                'bets' => $returnArray
            ]
        ];
    }

    private function getHistory()
    {
        $games = Game::query()->orderBy('id', 'DESC')->where('status', 2)->limit(16)->get();
        $returnValue = [];

        foreach ($games as $game) {
            $returnValue[] = [
                'multiplier' => $game->multiplier,
                'color' => $this->getGameColor($game->multiplier),
                'id' => $game->id
            ];
        }

        return $returnValue;
    }

    private function getGameColor($multiplier)
    {
        if ($multiplier > 0 && $multiplier <= 1.2) {
            return '#A42B3F';
        } else if ($multiplier > 1.2 && $multiplier <= 2) {
            return '#7355A2';
        } else if ($multiplier > 2 && $multiplier <= 4) {
            return '#F717F4';
        } else if ($multiplier > 4 && $multiplier <= 8) {
            return '#47B17B';
        } else if ($multiplier > 8 && $multiplier <= 25) {
            return '#FFFC00';
        } else {
            return '#0EC5E5';
        }
    }

    private function lastBet($user)
    {
        $game = $this->getGameInController();
        $bet = Bet::query()->with(['user', 'winItem'])->where([['game_id', $game->id], ['user_id', $user->id], ['is_win', 1]])->first();

        $returnArray = [];

        if ($bet) {
            $returnArray = [
                'bank' => $bet->bank,
                'win' => $bet->win,
                'multiplier' => $bet->multiplier,
                'autoWithdraw' => $bet->auto_withdraw,
                'item' => [
                    'image' => $bet->winItem->image,
                    'rarity' => $this->getColorRarity($bet->winItem->rarity)
                ]
            ];
        }

        return $returnArray;
    }

    private function userBet($user)
    {
        $game = $this->getGameInController();
        $bet = Bet::query()->with(['user'])->where([['game_id', $game->id], ['user_id', $user->id], ['is_win', '<>', 1]])->first();

        $returnArray = [];

        if ($bet) {
            $items = [];

            foreach (json_decode($bet->items) as $item) {
                $items[] = [
                    'image' => $item->item->image,
                    'rarity' => $this->getColorRarity($item->item->rarity),
                    'price' => $item->item->price
                ];
            }

            usort($items, function ($a, $b) {
                return ($b['price'] < $a['price']) ? -1 : 1;
            });

            $returnArray = [
                'items' => array_slice($items, 0, 3),
                'bank' => $bet->bank,
                'autoWithdraw' => $bet->auto_withdraw
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
