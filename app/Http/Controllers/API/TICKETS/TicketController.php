<?php

namespace App\Http\Controllers\API\TICKETS;
use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Assigned;

class TicketController extends Controller
{

    /**
     * Handle an incoming authentication request.
     *
     *  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
            $tickets = Ticket::all();
            $assign = Assigned::all();
            $assigned_tickets_array = array();
            foreach ($assign as $assigned_ticket) {
                $assigned_tickets_array[] = $assigned_ticket->ticket_id;
            }
            $tickets_array = array();
            foreach ($tickets as $ticket) {
                if (in_array($ticket->id, $assigned_tickets_array)) {
                    $ticket->assigned = true;
                    $ticket->assigned_user = $assigned_ticket->user_id;
                } else {
                    $ticket->assigned = false;
                }
                $tickets_array[] = $ticket;
               }
            return response()->json($tickets, 200);
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //update a ticket


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //delete a ticket



    }
}
