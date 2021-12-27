<?php

namespace App\Http\Controllers;

use App\Giveaway;
use App\GiveawayBet;
use App\Inventory;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GiveawayController extends Controller
{
    public function getActiveGiveaways()
    {
        $activeGiveaway = Giveaway::query()->with(['item'])->where('status', 0)->orderBy('id', 'ASC')->first();
        $historyGiveaways = [];

        if ($activeGiveaway) {
            $activeGiveaway->item->rarity = $this->getColorRarity($activeGiveaway->item->rarity);
            $activeGiveaway->members = GiveawayBet::query()->where('giveaway_id', $activeGiveaway->id)->count('id');
            $activeGiveaway->end_time = Carbon::create($activeGiveaway->end_time)->timestamp;
        }

        foreach (Giveaway::query()->with(['item', 'user'])->where('status', 1)->orderBy('id', 'desc')->get() as $giveaway) {
            $place = GiveawayBet::query()->where([['giveaway_id', $giveaway->id], ['user_id', $giveaway->winner_id]])->first();

            if (!$place) continue;

            $historyGiveaways[] = [
                'item' => [
                    'image' => $giveaway->item->image
                ],
                'user' => [
                    'avatar' => $giveaway->user->avatar,
                    'steamid' => $giveaway->user->steamid,
                    'username' => $giveaway->user->username
                ],
                'place' => $place->id,
                'created_at' => $giveaway->created_at->format('d.m.y H:s')
            ];
        }

        return [
            'activeGiveaway' => $activeGiveaway,
            'historyGiveaways' => $historyGiveaways
        ];
    }

    public function getMyActiveGiveaway(Request $r)
    {
        if ($r->user()) {
            $activeGiveaway = Giveaway::query()->with(['item'])->where('status', 0)->orderBy('id', 'ASC')->first();

            if (!$activeGiveaway) {
                return response()->json(false);
            }

            $place = GiveawayBet::query()->where([['giveaway_id', $activeGiveaway->id], ['user_id', $r->user()->id]])->first();

            if (!$place) {
                return response()->json(false);
            }

            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function setMyActive(Request $r)
    {
        $activeGiveaway = Giveaway::query()->with(['item'])->where('status', 0)->orderBy('id', 'ASC')->first();

        if (!$activeGiveaway) {
            return [
                'success' => false,
                'message' => 'giveaway'
            ];
        }

        $place = GiveawayBet::query()->where([['giveaway_id', $activeGiveaway->id], ['user_id', $r->user()->id]])->first();

        if ($place) {
            return [
                'success' => false,
                'message' => 'member'
            ];
        }

        $payment = Payment::query()->where([['user_id', $r->user()->id], ['status', 1]])->sum('sum');

        if ($payment < $activeGiveaway->min_payment) {
            return [
                'success' => false,
                'message' => 'payment'
            ];
        }

        GiveawayBet::query()->create([
            'giveaway_id' => $activeGiveaway->id,
            'user_id' => $r->user()->id
        ]);

        return [
            'success' => true,
            'message' => 'join'
        ];
    }

    public function getRaffle(Request $r)
    {
        $isActiveGiveaway = Giveaway::query()->with(['item'])->where('status', 0)->orderBy('id', 'ASC')->first();

        if ($isActiveGiveaway) {
            if (Carbon::create($isActiveGiveaway->end_time)->timestamp < Carbon::now()->timestamp) {
                if ($isActiveGiveaway->winner_id) {
                    Inventory::query()->create([
                        'user_id' => $isActiveGiveaway->winner_id,
                        'item_id' => $isActiveGiveaway->item_id
                    ]);
                    $isActiveGiveaway->update([
                        'status' => 1
                    ]);
                } else {
                    $winner = GiveawayBet::query()->where('giveaway_id', $isActiveGiveaway->id)->inRandomOrder()->first();
                    if ($winner) {
                        Inventory::query()->create([
                            'user_id' => $winner->user_id,
                            'item_id' => $isActiveGiveaway->item_id
                        ]);
                        $isActiveGiveaway->update([
                            'winner_id' => $winner->user_id,
                            'status' => 1
                        ]);
                    } else {
                        $isActiveGiveaway->update([
                            'status' => 1
                        ]);
                    }
                }
            }
        }

        return $this->getActiveGiveaways();
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
}
