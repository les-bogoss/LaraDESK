<?php

namespace App\Http\Controllers\API\TICKETS;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Ticket_content;

use Illuminate\Http\Request;
use App\Models\Assigned;

class TicketController extends Controller
{

    /**
     * get all ticket header
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
            if ($user->hasPerm('read-ticket')) {
                $tickets = Ticket::all();
            } else {
                $tickets = Ticket::all()->where('user_id', $user->id);
            }
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
                return response()->json(['message' => 'Ticket created ID : ' . $ticket->id . ''], 201);
            } else {
                return response()->json(['error' => 'Ticket not created'], 500);
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
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
            //get ticket by id
            $ticket = Ticket::find($request->id);
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
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
            //get ticket by id
            $ticket = Ticket::find($request->id);
            if ($ticket) {
                //verify if user is assigned to ticket or is admin
                if ($user->id === $ticket->assignedUser || $user->hasPerm('update-ticket')) {
                    if (Ticket::update_header($request->id, $request->title, $request->category_id, $request->rating, $request->category, $request->status)) {
                        return response()->json(['message' => 'Ticket updated'], 200);
                    } else {
                        return response()->json(['error' => 'Ticket not updated'], 500);
                    };
                }
            } else {
                return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }

    /**
     * add content to a ticket
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\JsonReponse
     */

    public function add_content(Request $request, Ticket $ticket)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
            //get ticket by id
            $ticket = Ticket::find($request->id);
            if ($ticket) {
                //verify if the user is assigned to ticket or is owner of the ticket
                if ($user->id === $ticket->assignedUser || $user->id === $ticket->user_id) {
                    //create ticket content
                    if (Ticket::add_content($request->id, $user->id, $request->content_type, $request->text)) {
                        return response()->json(['message' => 'Ticket content added'], 200);
                    } else {
                        return response()->json(['error' => 'Ticket content not added'], 500);
                    };
                } else {
                    return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                }
            }
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
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
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
                    };
                } else {
                    return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                }
            }
        }
    }


    /**
     * Remove ticket content by ticket content id and ticket id.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete_content(Request $request, Ticket $ticket)
    {
        //delete a ticket content
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
            //get ticket by id
            $ticket = Ticket::find($request->id);
            if ($ticket) {
                //verify if is owner of the ticket
                //verify if the ticket content exists
                $ticket_content = Ticket_Content::where('id', $request->contentId)->where('ticket_id', $ticket->id)->first();
                if ($ticket_content) {
                    if ($user->id ===  Ticket_Content::find($request->contentId)->user_id || $user->hasPerm('delete-ticket')) {
                        //delete ticket content
                        if (Ticket::delete_content($request->id, $request->contentId)) {
                            return response()->json(['message' => 'Ticket content deleted'], 200);
                        } else {
                            return response()->json(['error' => 'Ticket content not deleted'], 500);
                        };
                    } else {
                        return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                    }
                } else {
                    return response()->json(['error' => 'Ticket content not found'], 404);
                }
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }

    /**
     * get all tickets content by ticket id
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */

    public function show_content(Request $request, Ticket $ticket)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();

        if ($user) {
            //get ticket by id
            $ticket = Ticket::find($request->id);
            if ($ticket) {
                //verify if the user is assigned to ticket or is owner of the ticket
                if ($user->id === $ticket->assignedUser || $user->id === $ticket->user_id || $user->hasPerm('read-ticket')) {
                    //get ticket content

                    $content = Ticket::where('id', $ticket->id)->first()->ticket_content()->get();
                    return response()->json($content, 200);
                } else {
                    return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                }
            } else {
                return response()->json(['error' => 'Ticket not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }
}
