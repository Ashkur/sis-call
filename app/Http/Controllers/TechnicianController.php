<?php

namespace App\Http\Controllers;

use App\Department;
use App\Technician;
use App\Ticket;
use App\Events\TicketAnswered;
use App\Http\Resources\TechnicianResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class TechnicianController extends Controller
{
    
    public function index()
    {
        try {
            return TechnicianResource::collection(Technician::all());
        } catch (\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    
    public function store(Request $request)
    {
        try{
            $technician = new Technician;
            $technician->name = $request->name;
            $technician->login = $request->login;
            $technician->password = Hash::make('secret');

            $department = Department::find($request->department);
            $department->technicians()->save($technician);

            return response()->json(new TechnicianResource($technician), 201);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    
    public function show(Technician $technician)
    {
        try {
            return new TechnicianResource($technician);
        } catch (\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    
    public function update(Request $request, Technician $technician)
    {
        try{
            $technician->name = $request->name;
            $technician->login = $request->login;

            $department = Department::find($request->department);
            $department->technicians()->save($technician);

            return response()->json(new TechnicianResource($technician), 200);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    
    public function destroy(Technician $technician)
    {
        try {
            $technician->delete();
            return response()->json($technician, 200);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    public function answerCall(Request $request) {
        
        try {
            $technician = Technician::find($request->technician);
            $ticket = Ticket::find($request->ticket);
            $ticket->status = 'EM ANDAMENTO';

            $technician->tickets()->save($ticket);

            $this->notifyAnswerCall();
            
            return response()->json('registered', 200);
        } catch(\Exception $e) {
            return response()->json($e->getCode(), 400);
        }
    }

    public function notifyAnswerCall() {
        event(new TicketAnswered());
    }
}
