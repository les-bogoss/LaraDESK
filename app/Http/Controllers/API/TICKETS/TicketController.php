<?php

namespace App\Http\Controllers\API\TICKETS;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Ticket_content;
use App\Models\Ticket_category;

use Illuminate\Http\Request;
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
            //get all ticket
            $tickets = Ticket::all();
            //get assigned tickets
            $assigned_tickets = Assigned::all();
            //foreach assigned tickets get ticket id
            $assigned_tickets_array = array();
            foreach ($assigned_tickets as $assigned_ticket) {
                $assigned_tickets_array[] = $assigned_ticket->ticket_id;
            }
            foreach ($tickets as $ticket) {
                //if ticket id is in assigned tickets array then set assigned to true and get user id
                if (in_array($ticket->id, $assigned_tickets_array)) {
                    $ticket->assigned = true;
                    $ticket->assigned_user = $assigned_ticket->user_id;
                } else {
                    $ticket->assigned = false;
                }
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
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();
        //create ticket if user
        if ($user) {
            $ticket = new Ticket();
            $ticket->user_id = $user->id;
            $ticket->title = $request->title;
            $ticket->category_id = $request->category_id;
            $ticket->save();

            //create ticket content
            $ticket_content = new Ticket_content();
            $ticket_content->ticket_id = $ticket->id;
            $ticket_content->type = $request->content_type;
            $ticket_content->text = $request->text;
            $ticket_content->user_id = $user->id;
            $ticket_content->save();

            // if ticket is created 
            if ($ticket && $ticket_content) {
                return response()->json(['message' => 'Ticket created ID : ' . $ticket->id . ''], 200);
            } else {
                return response()->json(['error' => 'Ticket not created'], 404);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
        /**
         * Display the specified resource.
         *
         * @param  \App\Models\Ticket  $ticket
         * @return \Illuminate\Http\JsonResponse
         */
    }
    public function show(Request $request)
    {
        //verify api_token get in header

        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
            //get ticket by id
            $ticket = Ticket::find($request->id);
            if ($ticket) {
                //get assigned ticket by ticket id
                $assigned_ticket = Assigned::where('ticket_id', $ticket->id)->first();
                if ($assigned_ticket) {
                    //if ticket is assigned then set assigned to true and get user id
                    $ticket->assigned = true;
                    $ticket->assigned_user = $assigned_ticket->user_id;
                } else {
                    $ticket->assigned = false;
                }
                return response()->json($ticket, 200);
            } else {
                return response()->json(['error' => 'Ticket not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
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
