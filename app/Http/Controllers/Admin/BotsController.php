<?php

namespace App\Http\Controllers\Admin;

use App\Bet;
use App\ChatFake;
use App\Http\Controllers\Controller;
use App\User;
use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class BotsController extends Controller
{
    public function index()
    {
        return view('admin.bots.index');
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

        return view('admin.bots.edit', compact('user', 'withdraw', 'profit', 'withdraws', 'bets'));
    }

    public function editPost($id, Request $r)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return redirect()->back();
        }

        $user->update([
            'username' => $r->get('username')
        ]);

        return redirect('/jhasdjashdas/bots/edit/' . $id);
    }

    public function user($id)
    {
        User::query()->find($id)->update([
            'is_fake' => 0
        ]);

        return redirect()->back();
    }

    public function create()
    {
        return view('admin.bots.create');
    }

    public function createPost(Request $r)
    {
        $steamid = $r->get('steamid');

        $url = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=4889188262C2B80D2EB8133EF5FC69F2&steamids='. $steamid), true);

        if (!isset($url['response']['players'][0])) {
            return redirect()->back();
        }

        $player = $url['response']['players'][0];

        User::create([
            'steamid' => $player['steamid'],
            'username' => $player['personaname'],
            'avatar' => $player['avatarfull'],
            'referral_code' => Str::random(10),
            'referral_use' => null,
            'api_token' => Str::random(60),
            'is_fake' => 1
        ]);

        return redirect('/jhasdjashdas/bots');
    }

    public function messageCreate()
    {
        return view('admin.bots.create_message');
    }

    public function messageCreatePost(Request $r)
    {
        ChatFake::query()->create($r->all());

        return redirect('/jhasdjashdas/bots');
    }

    public function messageEdit($id)
    {
        $msg = ChatFake::query()->find($id);

        if (!$msg) {
            return redirect()->back();
        }

        return view('admin.bots.edit_message', compact('msg'));
    }

    public function messageEditPost($id, Request $r)
    {
        $msg = ChatFake::query()->find($id);

        if (!$msg) {
            return redirect()->back();
        }

        $msg->update($r->all());

        return redirect('/jhasdjashdas/bots');
    }

    public function messageDelete($id)
    {
        ChatFake::query()->find($id)->delete();

        return redirect('/jhasdjashdas/bots');
    }

    public function sendMessage(Request $r)
    {
        $user = User::query()->find($r->get('user_id'));

        if (!$user) {
            return redirect()->back();
        }

        $message = $r->get('message');

        if (empty($message)) {
            return redirect()->back();
        }

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
            'message' => htmlspecialchars($message),
            'time' => Carbon::now()->format('H:i')
        ];

        Redis::rpush('chat', json_encode($returnValue));
        Redis::publish('newMessage', json_encode($returnValue));

        return redirect()->back();
    }
}
