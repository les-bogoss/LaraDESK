<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket_content;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTicketRequest;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasPerm('read-ticket')) {
            $tickets = Ticket::all();
            return view('tickets', compact('tickets'));
        } else {
            $tickets = Ticket::where('user_id', Auth::user()->id)->get();
            return view('tickets', compact('tickets'));
        }
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
    public function store(CreateTicketRequest $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'integer', 'between:1,3'],
        ]);

        $ticket = new Ticket;
        $ticket->user_id = Auth::user()->id;
        $ticket->title = $request->title;
        $ticket->priority = $request->priority;
        $ticket->category_id = 1;
        $ticket->save();
        return redirect()->route('tickets.show', $ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {

        $statusColor = [
            '1' => 'ticket-status-open',
            '2' => 'ticket-status-assigned',
            '3' => 'ticket-status-waiting',
            '4' => 'ticket-status-closed',
            '5' => 'ticket-status-solved',
        ];
        $categoryColor = [
            '1' => 'ticket-category-bug',
            '2' => 'ticket-category-feature',
            '3' => 'ticket-category-question',
        ];
        $priorityColor = [
            '0' => 'ticket-priority-low',
            '1' => 'ticket-priority-low',
            '2' => 'ticket-priority-medium',
            '3' => 'ticket-priority-high',
        ];

        if (!Auth::user()->hasPerm('read-ticket')) {
            if (Auth::user()->id != $ticket->user_id) {
                return redirect()->route('tickets.index');
            } else {
                $tickets = Ticket::where('user_id', Auth::user()->id)->get();
                return view('tickets', compact('tickets', 'ticket', 'statusColor', 'categoryColor', 'priorityColor'));
            }
        } else {
            $tickets = Ticket::all();
            return view('tickets', compact('tickets', 'ticket', 'statusColor', 'categoryColor', 'priorityColor'));
        }
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
    public function editStatus(Request $request, Ticket $ticket)
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

    public function editPriority(Request $request, Ticket $ticket)
    {


        $ticket->priority = $request->input('ticket_priority');
        $ticket->save();
        return redirect()->back();
    }
    public function editRating(Request $request, Ticket $ticket)
    {


        $ticket->rating = $request->input('rating');
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

        if ($request->input('content') != "") {
            if (Auth::user()->hasPerm("update-ticket") || Auth::user()->id == $ticket->user_id) {
                if ($ticket->status_id < 4) {
                    $content = new Ticket_content;
                    $content->text = nl2br(e($request->input('content')));
                    $content->type = 'text';
                    $content->user_id = Auth::user()->id;
                    $content->ticket_id = $ticket->id;
                    $content->save();
                }
            }
        }

        return redirect()->back();
    }
    public function deleteContent(Request $request, Ticket $ticket, Ticket_content $content)
    {
        if (Auth::user()->hasPerm("delete-ticket") || Auth::user()->id == $content->user_id) {
            $content->delete();
        }
        return redirect()->back();
    }
}
