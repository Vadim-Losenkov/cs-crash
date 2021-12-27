<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{
    public function sendMessage(Request $r)
    {
        if ($this->config->ban_all_chat) {
            return response()->json([
                'message' => 'stop',
                'success' => false
            ]);
        }

        $message = $r->get('message');

        $val = \Validator::make($r->all(), [
            'message' => 'required|string|max:100'
        ], [
            'required' => 'empty',
            'string' => 'string',
            'max' => 'max',
        ]);
        $error = $val->errors();

        if ($val->fails()) {
            return response()->json(['message' => $error->first('message'), 'type' => 'error']);
        }

        if (preg_match('/^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/', $message)) {
            return response()->json([
                'message' => 'url',
                'success' => false
            ]);
        }

        if ($r->user()->is_ban_chat) {
            return response()->json([
                'message' => 'banned',
                'success' => false
            ]);
        }

        if (\Cache::has('addmsg.user.' . $r->user()->id)) {
            return response()->json([
                'message' => 'fast',
                'success' => false
            ]);
        }
        \Cache::put('addmsg.user.' . $r->user()->id, '', 2);

        $returnValue = [
            'id' => Str::random(16),
            'user' => [
                'steamid' => $r->user()->steamid,
                'avatar' => $r->user()->avatar,
                'username' => htmlspecialchars($r->user()->username),
                'is_admin' => $r->user()->is_admin,
                'is_moderator' => $r->user()->is_moderator,
                'is_ban_chat' => $r->user()->is_ban_chat
            ],
            'message' => htmlspecialchars($message),
            'time' => Carbon::now()->format('H:i')
        ];

        Redis::rpush('chat', json_encode($returnValue));
        Redis::publish('newMessage', json_encode($returnValue));

        return response()->json([
            'success' => true
        ]);
    }

    public function ban(Request $r)
    {
        $steamId = $r->get('steamId');

        if (!$r->user()->is_admin && !$r->user()->is_moderator) {
            return [
                'type' => 'error',
                'message' => 'prav'
            ];
        }

        if ($steamId === $r->user()->steamid) {
            return [
                'type' => 'error',
                'message' => 'yourself'
            ];
        }

        User::query()->where('steamid', $steamId)->update(['is_ban_chat' => 1]);

        $value = Redis::lrange('chat', 0, -1);

        $json = json_encode($value);
        $json = json_decode($json);

        Redis::del('chat');

        foreach ($json as $newchat) {
            $val = json_decode($newchat);

            if ($val->user->steamid == $steamId) {
                $val->user->is_ban_chat = 1;
            }

            Redis::rpush('chat', json_encode($val));
        }

        Redis::publish('loadChat', json_encode($this->getMessages()));

        return [
            'type' => 'success',
            'message' => 'blocked'
        ];
    }

    public function unBan(Request $r)
    {
        $steamId = $r->get('steamId');

        if (!$r->user()->is_admin && !$r->user()->is_moderator) {
            return [
                'type' => 'error',
                'message' => 'prav'
            ];
        }

        User::query()->where('steamid', $steamId)->update(['is_ban_chat' => 0]);

        $value = Redis::lrange('chat', 0, -1);

        $json = json_encode($value);
        $json = json_decode($json);

        Redis::del('chat');

        foreach ($json as $newchat) {
            $val = json_decode($newchat);

            if ($val->user->steamid == $steamId) {
                $val->user->is_ban_chat = 0;
            }

            Redis::rpush('chat', json_encode($val));
        }

        Redis::publish('loadChat', json_encode($this->getMessages()));

        return [
            'type' => 'success',
            'message' => 'unblocked'
        ];
    }

    public function delete(Request $r)
    {
        $id = $r->get('id');

        if (!$r->user()->is_admin && !$r->user()->is_moderator) {
            return [
                'type' => 'error',
                'message' => 'prav'
            ];
        }

        $value = Redis::lrange('chat', 0, -1);
        $json = json_encode($value);
        $json = json_decode($json);
        foreach ($json as $newchat) {
            $val = json_decode($newchat);

            if ($val->id == $id) {
                Redis::lrem('chat', 1, json_encode($val));
                Redis::publish('loadChat', json_encode($this->getMessages()));
            }
        }

        return [
            'type' => 'success',
            'message' => 'deleted'
        ];
    }

    public function getMessages()
    {
        $value = Redis::lrange('chat', 0, -1);
        $i = 0;
        $returnValue = [];
        $value = array_reverse($value);

        foreach ($value as $key => $newchat[$i]) {
            if ($i > 20) {
                break;
            }
            $value2[$i] = json_decode($newchat[$i], true);

            $returnValue[$i] = [
                'id' => $value2[$i]['id'],
                'user' => $value2[$i]['user'],
                'message' => $value2[$i]['message'],
                'time' => $value2[$i]['time']
            ];
            $i++;
        }

        return array_reverse($returnValue);
    }
}
