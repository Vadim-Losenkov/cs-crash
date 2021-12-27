<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.admins.index');
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function createPost(Request $request)
    {
        Admin::query()->create([
            'username' => $request['username'],
            'password' => hash('sha256', $request['password'])
        ]);

        return redirect('/jhasdjashdas/admins');
    }

    public function edit($id)
    {
        $user = Admin::query()->find($id);

        if (!$user) {
            return redirect()->back();
        }

        return view('admin.admins.edit', compact('user'));
    }

    public function editPost($id, Request $r)
    {
        $user = Admin::query()->find($id);

        if (!$user) {
            return redirect()->back();
        }

        if ($user->password !== $r->get('password')) {
            $user->update([
                'password' => hash('sha256', $r->get('password'))
            ]);
        }

        $user->update([
            'username' => $r->get('username')
        ]);

        return redirect('/jhasdjashdas/admins/edit/' . $id);
    }

    public function delete($id)
    {
        Admin::query()->find($id)->delete();

        return redirect()->back();
    }
}
