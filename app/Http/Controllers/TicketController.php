<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ticket;
use App\Events\TicketOpen;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    public function index() {
        return TicketResource::collection(Ticket::all());
    }

    public function store(Request $request) {
        $user = \App\User::find(10000);
        $ticket = new Ticket;
        $ticket->ocurrence = $request->ocurrence;
        $ticket->description = $request->description;
        $ticket->status = 'PENDENTE';

        try{
            $user->tickets()->save($ticket);
            $this->sendTicket($user->name);
        }catch (Exception $e){
            return abort('500', $e->getMessage());
        }
        
    }

    public function sendTicket($username) {
        event(new TicketOpen($username));
    }
}
