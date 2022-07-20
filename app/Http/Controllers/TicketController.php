<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Jobs\SendEmailJob;
use App\Models\Assigned;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\Ticket_content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
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
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateTicketRequest $request): \Illuminate\Http\RedirectResponse
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
        $mailData = [
            'subject' => 'Ticket Created - LaraDESK',
            'title' => 'Ticket created with id #' . $ticket->id,
            'email' => Auth::user()->email,
            'view' => 'emails.ticketUpdate',
            'body' => 'Your <a href="https://34.140.17.43/tickets/' . $ticket->id . '">ticket #' . $ticket->id . '</a> has been created ,we are on it !',
        ];

        dispatch(new SendEmailJob($mailData));

        return redirect()->route('tickets.show', $ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Contracts\View\View
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
        $technicians = Role::where('name', 'Technician')->first()->users;

        if (!Auth::user()->hasPerm('read-ticket')) {
            if (Auth::user()->id != $ticket->user_id) {
                return redirect()->route('tickets.index');
            } else {
                $tickets = Ticket::where('user_id', Auth::user()->id)->get();

                return view('tickets', compact('tickets', 'ticket', 'statusColor', 'categoryColor', 'priorityColor', 'technicians'));
            }
        } else {
            $tickets = Ticket::all();

            return view('tickets', compact('tickets', 'ticket', 'statusColor', 'categoryColor', 'priorityColor', 'technicians'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    // public function edit(Ticket $ticket)
    // {
    //     //
    // }

    /**
     * Edit the status of the current ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editStatus(Request $request, Ticket $ticket): \Illuminate\Http\RedirectResponse
    {
        $statusId = [
            'OUVERT' => '1',
            'ATTRIBUÉ' => '2',
            'EN ATTENTE' => '3',
            'CLOS' => '4',
            'RÉSOLU' => '5',
        ];

        $mailData = [
            'subject' => 'Ticket Status updated - LaraDESK',
            'title' => 'Status updated on ticket #' . $ticket->id,
            'email' => Auth::user()->email,
            'view' => 'emails.ticketUpdate',
            'body' => '<a href="https://34.140.17.43/tickets/' . $ticket->id . '">Ticket #' . $ticket->id . '</a> has been updated to <strong>' . $request->input('ticket_status') . '</strong>',
        ];

        $ticket->status_id = $statusId[$request->input('ticket_status')];

        dispatch(new SendEmailJob($mailData));

        $ticket->save();

        return redirect()->back();
    }

    /**
     * Edit the priority of the current ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editPriority(Request $request, Ticket $ticket): \Illuminate\Http\RedirectResponse
    {
        $ticket->priority = $request->input('ticket_priority');
        $ticket->save();

        return redirect()->back();
    }

    /**
     * Edit the rating of the current ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editRating(Request $request, Ticket $ticket): \Illuminate\Http\RedirectResponse
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
    // public function update(Request $request, Ticket $ticket)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Ticket $ticket): \Illuminate\Http\RedirectResponse
    {
        $ticket->delete();

        if (str_contains(url()->previous(), '/dashboard/users')) {
            return redirect()->back();
        } else {
            return redirect()->route('tickets.index');
        }
    }

    /**
     * Create a new content for the current ticket
     *
     * @param  \App\Models\Ticket  $ticket
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createContent(Request $request, Ticket $ticket): \Illuminate\Http\RedirectResponse
    {
        if ($request->input('content') != '') {
            if (Auth::user()->hasPerm('update-ticket') || Auth::user()->id == $ticket->user_id) {
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

        $mailData = [
            'subject' => 'Ticket content update - LaraDESK',
            'title' => 'New content for the ticket #' . $ticket->id,
            'email' => Auth::user()->email,
            'view' => 'emails.ticketUpdate',
            'body' => '<a href="https://34.140.17.43/tickets/' . $ticket->id . '">Ticket #' . $ticket->id . '</a> has received a new content',
        ];

        dispatch(new SendEmailJob($mailData));

        return redirect()->back();
    }

    /**
     * Delete a content for the current ticket
     *
     * @param  \App\Models\Ticket_content  $content
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteContent(Request $request, Ticket_content $content): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasPerm('delete-ticket') || Auth::user()->id == $content->user_id) {
            $content->delete();
        }

        return redirect()->back();
    }

    /**
     * Edit the assigned technician for the current ticket
     *
     * @param  \App\Models\Ticket  $ticket
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editTechnician(Request $request, Ticket $ticket): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasPerm('update-ticket')) {
            if ($request->input('technician') != '') {
                // If the ticket already has a technician update the technician
                if (!isset($ticket->assignedUser->first()->id)) {
                    $assignee = new Assigned();
                    $assignee->user_id = $request->input('technician');
                    $assignee->ticket_id = $ticket->id;
                    $assignee->save();
                }
                // Else create a technician
                else {
                    $assignee = Assigned::where('ticket_id', $ticket->id)->first();
                    $assignee->user_id = $request->input('technician');
                    $assignee->update();
                }
            }

            return redirect()->back();
        }

        return redirect()->back();
    }
}
