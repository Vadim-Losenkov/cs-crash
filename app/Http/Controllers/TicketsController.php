<?php

namespace App\Http\Controllers;

use App\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function create(Request $r)
    {
        $ticket = $r->get('ticket');

        $val = \Validator::make($r->all(), [
            'ticket.title' => 'required|string|max:100|min:3',
            'ticket.message' => 'required|string|max:100|min:3'
        ], [
            'ticket.title.required' => 'title_empty',
            'ticket.title.string' => 'title_string',
            'ticket.title.max' => 'title_max',
            'ticket.title.min' => 'title_min',
            'ticket.message.required' => 'message_empty',
            'ticket.message.string' => 'message_string',
            'ticket.message.max' => 'message_max',
            'ticket.message.min' => 'message_min'
        ]);

        $error = $val->errors();

        if ($val->fails()) {
            if ($error->first('ticket.title')) {
                return response()->json(['success' => false, 'message' => $error->first('ticket.title'), 'type' => 'error']);
            }
            if ($error->first('ticket.message')) {
                return response()->json(['success' => false, 'message' => $error->first('ticket.message'), 'type' => 'error']);
            }
        }

        $oldTicket = Ticket::query()->where([['user_id', $r->user()->id], ['status', 0]])->first();

        if ($oldTicket) {
            return [
                'success' => false,
                'message' => 'close_last_ticket'
            ];
        }

        $messages = [];

        $messages[] = [
            'username' => $r->user()->username,
            'message' => $ticket['message'],
            'date' => Carbon::now()->format('d.m.Y H:i')
        ];

        Ticket::query()->create([
            'user_id' => $r->user()->id,
            'title' => $ticket['title'],
            'messages' => json_encode($messages),
            'last_admin' => 0,
            'status' => 0
        ]);

        return [
            'success' => true
        ];
    }

    public function get(Request $r)
    {
        return Ticket::query()->where('user_id', $r->user()->id)->orderBy('status', 'asc')->get();
    }

    public function getById(Request $r)
    {
        $id = $r->get('id');

        $ticket = Ticket::query()->where('user_id', $r->user()->id)->find($id);

        if (!$ticket) {
            return [
                'success' => false
            ];
        }

        return [
            'success' => true,
            'ticket' => [
                'id' => $ticket->id,
                'title' => $ticket->title,
                'messages' => json_decode($ticket->messages),
                'status' => $ticket->status
            ]
        ];
    }

    public function close(Request $r)
    {
        $id = $r->get('id');

        $ticket = Ticket::query()->where('user_id', $r->user()->id)->find($id);

        if (!$ticket) {
            return [
                'success' => false,
                'message' => 'ticket_not_found'
            ];
        }

        if ($ticket->status) {
            return [
                'success' => false,
                'message' => 'ticket_closed'
            ];
        }

        $ticket->update([
            'status' => 1
        ]);

        return [
            'success' => true
        ];
    }

    public function sendMessage(Request $r)
    {
        $id = $r->get('id');

        $ticket = Ticket::query()->where('user_id', $r->user()->id)->find($id);

        if (!$ticket) {
            return [
                'success' => false,
                'message' => 'ticket_not_found'
            ];
        }

        if ($ticket->status) {
            return [
                'success' => false,
                'message' => 'ticket_closed'
            ];
        }

        $message = $r->get('message');

        $val = \Validator::make($r->all(), [
            'message' => 'required|string|max:100|min:3'
        ], [
            'message.required' => 'message_empty',
            'message.string' => 'message_string',
            'message.max' => 'message_max',
            'message.min' => 'message_min'
        ]);

        $error = $val->errors();

        if ($val->fails()) {
            return response()->json(['success' => false, 'message' => $error->first('message'), 'type' => 'error']);
        }

        $messages = json_decode($ticket->messages);

        $messages[] = [
            'username' => $r->user()->username,
            'message' => $message,
            'date' => Carbon::now()->format('d.m.Y H:i')
        ];

        $ticket->update([
            'messages' => json_encode($messages),
            'last_admin' => 0
        ]);

        return [
            'success' => true,
            'messages' => json_decode($ticket->messages)
        ];
    }
}
