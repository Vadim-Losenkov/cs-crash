<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $supports = Ticket::query()->with(['user'])->orderBy('created_at', 'DESC')->get();

        return view('admin.support.index', compact('supports'));
    }

    public function chat($id)
    {
        $ticket = Ticket::query()->find($id);

        if (!$ticket) {
            return redirect()->back();
        }

        return view('admin.support.chat', compact('ticket'));
    }

    public function sendMessage($id, Request $r)
    {
        if (strlen($r->get('message')) < 0) {
            return redirect()->back();
        }

        $ticket = Ticket::query()->find($id);

        if (!$ticket) {
            return redirect()->back();
        }

        $messages = json_decode($ticket->messages, true);

        $messages[] = [
            'username' => 'Админ',
            'message' => $r->get('message'),
            'date' => Carbon::now()->format('d.m.Y H:i')
        ];

        $ticket->update([
            'messages' => json_encode($messages),
            'last_admin' => 1
        ]);

        return redirect()->back();
    }

    public function closeTicket($id)
    {
        $ticket = Ticket::query()->find($id);

        if (!$ticket) {
            return redirect()->back();
        }

        $ticket->update([
            'status' => 1
        ]);

        return redirect()->back();
    }
}
