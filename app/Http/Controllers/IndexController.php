<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('app');
    }

    public function getConfig()
    {
        return [
            'dollar' => $this->config->dollar,
            'site_name' => $this->config->site_name,
            'vk_group' => $this->config->vk_group,
            'percent_referral' => $this->config->percent_referral,
            'hide_giveaway' => $this->config->hide_giveaway
        ];
    }
}
