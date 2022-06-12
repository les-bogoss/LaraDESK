<?php

namespace App\Http\Controllers\API\TICKETS;
use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

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
            return response()->json(['tickets' => $tickets], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
