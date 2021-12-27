<?php

namespace App\Http\Controllers\Admin;

use App\AllItem;
use App\Http\Controllers\AllItemsController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

ini_set('max_execution_time', '0');

class ItemsController extends Controller
{
    public function index()
    {
        return view('admin.items.index');
    }

    public function create()
    {
        return view('admin.items.create');
    }

    public function createPost(Request $request)
    {
        AllItem::query()->create($request->all());

        return redirect('/jhasdjashdas/items');
    }

    public function edit($id)
    {
        $item = AllItem::query()->find($id);

        if (!$item) {
            return redirect()->back();
        }

        return view('admin.items.edit', compact('item'));
    }

    public function editPost($id, Request $r)
    {
        AllItem::query()->find($id)->update($r->all());

        return redirect('/jhasdjashdas/items/edit/' . $id);
    }

    public function delete($id)
    {
        AllItem::query()->find($id)->delete();

        return redirect()->back();
    }

    public function prices($appId)
    {
        if ($appId === 730) {
            $prices = json_decode(file_get_contents('https://market.csgo.com/api/v2/prices/RUB.json'), true);
        } else {
            $prices = json_decode(file_get_contents('https://market.dota2.net/api/v2/prices/RUB.json'), true);
        }

        if (!$prices['success']) {
            return 'Ошибка загрузки цен';
        }

        $newPrices = [];

        foreach ($prices['items'] as $price) {
            $newPrices[$price['market_hash_name']] = $price['price'];
        }

        foreach (AllItem::query()->get() as $itemDB) {
            if ($appId === 730) {
                $fullName = $itemDB->market_hash_name . ' (' . $itemDB->exterior . ')';
                $fullName = str_replace('StatTrak™', '', $fullName);
            } else {
                $fullName = $itemDB->market_hash_name;
            }

            if (!isset($newPrices[$fullName])) {
                continue;
            }

            $itemDB->update(['price' => round($newPrices[$fullName] / 69.58, 2)]);
        }

        return redirect()->back();
    }
}
