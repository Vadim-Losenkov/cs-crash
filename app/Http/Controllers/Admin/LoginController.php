<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function login(Request $r)
    {
        $username = $r->get('username');
        $password = $r->get('password');

        $admin = Admin::query()->where([['username', $username], ['password', hash('sha256', $password)]])->first();

        if (!$admin) {
            return redirect()->back();
        }

        Auth::guard('admins')->login($admin, true);

        return redirect('/jhasdjashdas');
    }

    public function logout()
    {
        Auth::guard('admins')->logout();

        return redirect('/jhasdjashdas');
    }
}
