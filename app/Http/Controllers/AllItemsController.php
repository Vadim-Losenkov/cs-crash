<?php

namespace App\Http\Controllers;

use App\AllItem;
use Illuminate\Http\Request;

ini_set('max_execution_time', '0');

class AllItemsController extends Controller
{
    public function fill()
    {
        $appId = 570;

        $site = 'https://market.csgo.com';

        if ($appId === 570) {
            $site = 'https://market.dota2.net';
        }

        $prices = json_decode(file_get_contents($site . '/api/v2/prices/RUB.json'), true);

        if (!$prices['success']) {
            return 'Ошибка загрузки цен';
        }

        $newPrices = [];

        foreach ($prices['items'] as $price) {
            $newPrices[$price['market_hash_name']] = $price['price'];
        }

        foreach (AllItem::query()->where('appId', $appId)->get() as $itemDB) {
            if ($appId === 730) {
                $fullName = $itemDB->market_hash_name . ' (' . $itemDB->exterior . ')';
                $fullName = str_replace('StatTrak™', '', $fullName);
            } else {
                $fullName = $itemDB->market_hash_name;
            }

            if (!isset($newPrices[$fullName])) {
                $itemDB->delete();
                continue;
            }

            $itemDB->update(['price' => round($newPrices[$fullName] / 69.58, 2)]);
        }

        return 'Цены обновлены';
    }

    public function getList(Request $r)
    {
        try {
            $minItemPrice = AllItem::query()->orderBy('price', 'ASC')->first();
            $minPrice = floatval($r->get('minPrice'));
            $maxPrice = floatval($r->get('maxPrice'));
            $marketHashName = $r->get('market_hash_name');
            $page = $r->get('page') ? $r->get('page') : 1;
            $appId = $r->get('appId');

            if ($maxPrice === 0.00 && $minPrice === 0.00 && strlen($marketHashName) === 0) {
                if ($r->user() && $r->user()->balance > $minItemPrice->price) {
                    $maxPrice = $r->user()->balance;
                } else {
                    $maxPrice = 99999;
                }

                $minPrice = 0;
                $marketHashName = '';
            } else if (strlen($marketHashName) > 0) {
                $maxPrice = 99999;
                $minPrice = 0;
            } else if ($maxPrice > 0.00 && $minPrice === 0.00) {
                $minPrice = 0;
                $marketHashName = '';
            } else if ($maxPrice === 0.00 && $minPrice > 0.00) {
                $maxPrice = 99999;
                $marketHashName = '';
            }

            return \Cache::remember('items_type_'. $minPrice .'_p_'. $maxPrice.'_name_'. $marketHashName .'_page_' . $page . '_appId_' . $appId, 3600, function () use ($minPrice, $maxPrice, $marketHashName, $page, $appId) {
                return AllItem::query()
                    ->where('price', '>=', $minPrice)
                    ->where('price', '<=', $maxPrice)
                    ->where('market_hash_name', 'like', '%' . $marketHashName . '%')
                    ->where('appId', $appId)
                    ->orderBy('price', 'DESC')
                    ->paginate(18, ['*'], 'page', $page);
            });
        } catch (\Exception $e) {
            return [];
        }
    }
}
