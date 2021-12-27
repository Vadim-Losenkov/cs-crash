<?php

namespace App\Http\Controllers\Admin;

use App\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function save(Request $r)
    {
        if ($r->get('bots_chat') !== $this->config->bots_chat) {
            Redis::publish('newFake', $r->get('bots_chat'));
        }

        Config::query()->find(1)->update($r->all());

        return redirect()->back();
    }
}
