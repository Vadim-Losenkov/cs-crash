<?php

namespace App\Http\Controllers\Admin;

use App\Bet;
use App\Http\Controllers\Controller;
use App\User;
use App\Withdraw;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function edit($id)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return redirect()->back();
        }

        $withdraw = 0;
        $withdraws = Withdraw::query()->with(['item'])->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $bets = Bet::query()->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        foreach ($withdraws as $win) {
            if ($win->status === 1) {
                $withdraw += $win->item->price;
            }
        }

        $profit = 0;

        foreach ($bets as $bet) {
            $profit += round($bet->win - $bet->bank, 2);
        }

        return view('admin.users.edit', compact('user', 'withdraw', 'profit', 'withdraws', 'bets'));
    }

    public function editPost($id, Request $r)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return redirect()->back();
        }

        $user->update([
            'username' => $r->get('username'),
            'balance' => $r->get('balance'),
            'is_ban' => $r->get('is_ban'),
            'is_ban_withdraw' => $r->get('is_ban_withdraw'),
            'is_ban_chat' => $r->get('is_ban_chat')
        ]);

        if ($r->get('type') === 'user') {
            $user->update([
                'is_admin' => 0,
                'is_moderator' => 0
            ]);
        } else if ($r->get('type') === 'moderator') {
            $user->update([
                'is_admin' => 0,
                'is_moderator' => 1
            ]);
        } else if ($r->get('type') === 'admin') {
            $user->update([
                'is_admin' => 1,
                'is_moderator' => 0
            ]);
        }

        return redirect('/jhasdjashdas/users/edit/' . $id);
    }

    public function delete($id)
    {
        User::query()->find($id)->delete();

        return redirect()->back();
    }
}
