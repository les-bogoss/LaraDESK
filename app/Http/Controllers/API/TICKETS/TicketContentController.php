<?php

namespace App\Http\Controllers\API\TICKETS;

use App\Http\Controllers\API\USER\UserController;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Ticket_content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TicketContentController extends Controller
{
    /**
     * get add ticket content
     *
     *  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function store(Request $request, Ticket $ticket)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);

        if ($user) {
            //get ticket by id
            $ticket = Ticket::find($request->ticketId);
            if ($ticket) {
                if ($user->hasVerifiedEmail()) {
                    //verify if the user is assigned to ticket or is owner of the ticket
                    if ($user->id === $ticket->assignedUser || $user->id === $ticket->user_id || $user->hasPerm('update-ticket')) {
                        //create ticket content
                        if ($request->text) {
                            if (TicketContentController::add_content($request->ticketId, $user->id, $request->content_type, $request->text, $request->file)) {
                                return response()->json(['message' => 'Ticket content added'], 200);
                            } else {
                                return response()->json(['error' => 'Ticket content not added'], 500);
                            }
                        } else {
                            return response()->json(['error' => 'Ticket content not added'], 500);
                        }
                    } else {
                        return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                    }
                } else {
                    return response()->json(['error' => 'Verify email adress'], 403);
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
    public function index(Request $request, Ticket $ticket)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            //get ticket by id
            if ($user->hasVerifiedEmail()) {
                $ticket = Ticket::find($request->ticketId);
                if ($ticket) {
                    //verify if the user is assigned to ticket or is owner of the ticket
                    if ($user->id === $ticket->assignedUser || $user->id === $ticket->user_id || $user->hasPerm('read-ticket')) {
                        //get ticket content
                        $content = Ticket::where('id', $ticket->id)->first()->ticket_content()->get();
                        //get first name and last name of user and link to user profile

                        foreach ($content as $key => $value) {

                            $user = Ticket_content::where('ticket_id', $ticket->id)
                                ->where('id', $content[$key]->id)->first()->User()->first();

                            $content[$key]->first_name = $user->first_name;
                            $content[$key]->last_name = $user->last_name;
                            $content[$key]->avatar = $user->avatar;
                        }
                        return response()->json(['ticket' => $content], 200);
                    } else {
                        return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                    }
                } else {
                    return response()->json(['error' => 'Ticket not found'], 404);
                }
            } else {
                return response()->json(['message' => 'Please verify your email address'], 403);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }

    /**
     * Remove ticket content by ticket content id and ticket id.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Ticket $ticket)
    {
        //delete a ticket content
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            //get ticket by id
            if ($user->hasVerifiedEmail()) {
                $ticket = Ticket::find($request->ticketId);
                if ($ticket) {
                    //verify if is owner of the ticket
                    //verify if the ticket content exists
                    $ticket_content = Ticket_Content::where('id', $request->contentId)->where('ticket_id', $ticket->id)->first();
                    if ($ticket_content) {
                        if ($user->id === Ticket_Content::find($request->contentId)->user_id || $user->hasPerm('delete-ticket')) {
                            //delete ticket content
                            if (Ticket_content::where('id', $request->contentId)->where('ticket_id', $request->ticketId)->delete()) {
                                return response()->json(['message' => 'Ticket content deleted'], 200);
                            } else {
                                return response()->json(['error' => 'Ticket content not deleted'], 500);
                            }
                        } else {
                            return response()->json(['error' => 'You are not assigned or owner of the ticket'], 403);
                        }
                    } else {
                        return response()->json(['error' => 'Ticket content not found'], 404);
                    }
                }
            } else {
                return response()->json(['message' => 'Please verify your email address'], 403);
            }
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }

    // add content where ticket is already created
    public static function add_content($ticket_id, $user_id, $content_type, $text, $file): bool
    {
        $ticket_content = new Ticket_content();
        $ticket_content->ticket_id = $ticket_id;
        if ($content_type && $text || $file) {
            $ticket_content->type = $content_type;

            $ticket_content->user_id = $user_id;
            if ($file) {
                //decode base 64 image
                $image = base64_decode($file);
                //create image name
                $image_name = time() . '_' . $user_id . '_' . $ticket_id . '_' . $content_type . '_.jpg';
                //create image path
                $image_path =  '/images/tickets_contents/' . $image_name;

                //save image
                Storage::disk('public')->put($image_path, $image);
                $ticket_content->media = "/storage" . $image_path;
            }
            $ticket_content->text = $text;

            $ticket_content->save();
            return true;
        } else {
            return false;
        }
    }
}
