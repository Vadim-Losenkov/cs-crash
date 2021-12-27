<?php

namespace App\Http\Controllers\Admin;

use App\Giveaway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GiveawayController extends Controller
{
    public function index()
    {
        return view('admin.giveaways.index');
    }

    public function delete($id)
    {
        Giveaway::query()->find($id)->delete();

        return redirect()->back();
    }

    public function create()
    {
        return view('admin.giveaways.create');
    }

    public function createPost(Request $r)
    {
        if ($r->get('winner_id') === 'NULL') {
            $winnerId = NULL;
        } else {
            $winnerId = $r->get('winner_id');
        }

        Giveaway::query()->create([
            'item_id' => $r->get('item_id'),
            'min_payment' => $r->get('min_payment'),
            'end_time' => $r->get('end_time'),
            'winner_id' => $winnerId,
            'status' => 0
        ]);

        return redirect('/jhasdjashdas/giveaways');
    }
}
