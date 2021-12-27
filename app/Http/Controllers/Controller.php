<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $config;

    public function __construct()
    {
        $this->config = Config::query()->find(1);

        view()->share('settings', $this->config);
    }
}
