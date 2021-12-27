<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Withdraw;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $withdrawAll = 0;
        $withdrawToday = 0;
        $withdrawWeek = 0;
        $withdrawMonth = 0;

        foreach (Withdraw::query()->with(['item'])->where('status', 1)->get() as $withdraw) {
            if ($withdraw->created_at >= \Carbon\Carbon::today()) {
                $withdrawToday += round($withdraw->item->price, 2);
            }
            if ($withdraw->created_at >= \Carbon\Carbon::today()->subDays(7)) {
                $withdrawWeek += round($withdraw->item->price, 2);
            }
            if ($withdraw->created_at >= \Carbon\Carbon::today()->subMonth()) {
                $withdrawMonth += round($withdraw->item->price, 2);
            }

            $withdrawAll += round($withdraw->item->price, 2);
        }

        return view('admin.index', compact('withdrawToday', 'withdrawWeek', 'withdrawMonth', 'withdrawAll'));
    }
}
