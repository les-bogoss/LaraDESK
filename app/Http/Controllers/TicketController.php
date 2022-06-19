<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket_content;
use Illuminate\Support\Facades\Auth;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::all();
        return view('tickets', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $tickets = Ticket::all();
        $statusColor = [
            '1' => 'ticket-status-open',
            '2' => 'ticket-status-assigned',
            '3' => 'ticket-status-waiting',
            '4' => 'ticket-status-closed',
            '5' => 'ticket-status-solved',
        ];
        return view('tickets', compact('tickets', 'ticket', 'statusColor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Edit the status of the current ticket
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function editStatus(Request $request,Ticket $ticket)
    {

        $statusId = [
            "OUVERT" => '1',
            "ATTRIBUÉ" => '2',
            "EN ATTENTE" => '3',
            "CLOS" => '4',
            "RÉSOLU" => '5',
        ];
        $ticket->status_id = $statusId[$request->input('ticket_status')];

        $ticket->save();
        return redirect()->back();
    }

    public function editPriority(Request $request,Ticket $ticket)
    {


        $ticket->priority = $request->input('ticket_priority');
        $ticket->save();
        return redirect()->back();
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
        $ticket->delete();

        if(str_contains(url()->previous(), '/dashboard/users')) {
            return redirect()->back();
        } else {
            return redirect()->route('ticket.index');
        }
    }

    /**
     * Create a new content for the current ticket
     *
     * @param  \App\Models\Ticket  $ticket
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createContent(Request $request, Ticket $ticket)
    {
        $content = new Ticket_content;
        $content->text = $request->input('content');
        $content->type = 'text';
        $content->user_id = Auth::user()->id;
        $content->ticket_id = $ticket->id;
        $content->save();

        return redirect()->back();
    }
}
