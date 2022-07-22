<?php

namespace App\Http\Controllers\API\TICKETS;

use App\Http\Controllers\API\USER\UserController;
use App\Http\Controllers\Controller;
use App\Models\Assigned;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * get all ticket header
     *
     *  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                //get all ticket
                if ($user->hasPerm('read-ticket')) {
                    $tickets = Ticket::orderByDesc('updated_at')->get();
                    //get first_name and last_name and avatar of user
                    foreach ($tickets as $ticket) {
                        $userd = Ticket::where('id', $ticket->id)->first()->user()->get()->first();
                        $ticket->first_name = $userd->first_name;
                        $ticket->last_name = $userd->last_name;
                        $ticket->avatar = $userd->avatar;
                    }
                } else {
                    $tickets = Ticket::all()->where('user_id', $user->id);
                }
                //get assigned tickets
                $assigned_tickets = Assigned::all();
                //foreach assigned tickets get ticket id
                $assigned_tickets_array = [];
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
                return response()->json(['error' => 'Verify email address'], 403);
            }
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
        $user = UserController::verify_token($api_token);
        //create ticket if user
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                $ticket = new Ticket();
                $ticket->user_id = $user->id;
                $ticket->title = $request->title;
                $ticket->priority = $request->priority;
                $ticket->category_id = $request->category_id;

                $ticket->save();

                //create ticket content

                // if ticket is created
                if ($ticket) {
                    return response()->json(['message' => 'Ticket created ID', 'id' => $ticket->id], 201);
                } else {
                    return response()->json(['error' => 'Ticket not created'], 500);
                }
            } else {
                return response()->json(['error' => 'Verify email address'], 403);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }

    /**
     * Display a tickets header by ticket id.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                //get ticket by id
                $ticket = Ticket::find((int) $request->id);
                if ($ticket) {
                    //verify user has permission to read ticket or is owner of ticket
                    if ($user->hasPerm('read-ticket') || $user->id == $ticket->user_id) {
                        //get assigned ticket by ticket id

                        $assigned_ticket = Ticket::where('id', $ticket->id)->first()->assignedUser()->get()->first();




                        if ($assigned_ticket) {
                            //if ticket is assigned then set assigned to true and get user id
                            $ticket->assigned = true;
                            $ticket->assigned_user = $assigned_ticket->id;
                        } else {
                            $ticket->assigned = false;
                        }

                        return response()->json($ticket, 200);
                    } else {
                        return response()->json(['error' => 'You have not the right permission to read the ticket'], 404);
                    }
                } else {
                    return response()->json(['error' => 'Ticket not found'], 404);
                }
            } else {
                return response()->json(['error' => 'Verify email address'], 403);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Ticket $ticket)
    {
        //update a ticket header
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                //get ticket by id
                $ticket = Ticket::find($request->id);
                if ($ticket) {
                    //verify if user is assigned to ticket or is admin
                    if ($user->id === $ticket->assignedUser || $user->hasPerm('update-ticket')) {
                        if (Ticket::update_header($request->id, $request->title, $request->priority, $request->rating, $request->category_id, $request->status_id)) {
                            return response()->json(['message' => 'Ticket updated'], 200);
                        } else {
                            return response()->json(['error' => 'Ticket not updated'], 500);
                        }
                    }
                } else {
                    return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                }
            } else {
                return response()->json(['error' => 'Verify email address'], 403);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }

    /**
     * Remove a ticket by ticket id.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Ticket $ticket, Request $request)
    {
        //delete a ticket
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                //get ticket by id
                $ticket = Ticket::find($request->id);
                if ($ticket) {
                    //verify if the user is assigned to ticket or has permission to delete ticket
                    if ($user->id === $ticket->assignedUser || $user->hasPerm('delete-ticket')) {
                        //delete ticket
                        if (Ticket::find($request->id)->delete()) {
                            return response()->json(['message' => 'Ticket deleted'], 200);
                        } else {
                            return response()->json(['error' => 'Ticket not deleted'], 500);
                        }
                    } else {
                        return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                    }
                }
            } else {
                return response()->json(['error' => 'Verify email address'], 403);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }
}
