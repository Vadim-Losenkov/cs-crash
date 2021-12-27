<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    public function index()
    {
        return view('admin.promocodes.index');
    }

    public function create()
    {
        return view('admin.promocodes.create');
    }

    public function createPost(Request $request)
    {
        Promocode::query()->create($request->all());

        return redirect('/jhasdjashdas/promocodes');
    }

    public function edit($id)
    {
        $promocode = Promocode::query()->find($id);

        if (!$promocode) {
            return redirect()->back();
        }

        return view('admin.promocodes.edit', compact('promocode'));
    }

    public function editPost($id, Request $r)
    {
        Promocode::query()->find($id)->update($r->all());

        return redirect('/jhasdjashdas/promocodes/edit/' . $id);
    }

    public function delete($id)
    {
        Promocode::query()->find($id)->delete();

        return redirect()->back();
    }
}
