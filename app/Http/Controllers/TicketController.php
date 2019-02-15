<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ticket;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    public function index() {
        return TicketResource::collection(Ticket::all());
    }

    public function store(Request $request) {
        $user = \App\User::find(1);
        $ticket = new Ticket;
        $ticket->description = $request->description;
        $ticket->status = 'PENDENTE';

        $user->tickets()->save($ticket);
    }

    // public function update(Request $request, Ticket $ticket) {
    //     $user = \App\User::find(1);

    //     $ticket->description = $request->description;
    //     $ticket->status =
    // }
}
