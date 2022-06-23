<?php

namespace App\Http\Controllers\API\DASHBOARD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Http\Controllers\API\USER\UserController;
use App\Models\Ticket_category;
use App\Models\Ticket_status;
use Carbon\Carbon;

class DashboardDataController extends Controller
{
    //
    public function getAllData(Request $request)
    {
        //verify api_token get in header
        $api_token = $request->header('Authorization');
        $user = UserController::verify_token($api_token);
        if ($user) {
            $users = User::count();
            $tickets = Ticket::count();
            //all status ticket
            $ticket_status = [];
            foreach (Ticket_status::all() as $i) {
                array_push($ticket_status, array($i->name => Ticket::where('status_id', $i->id)->count()));
            }



            //get cattegory ticket
            $ticket_category = [];
            foreach (Ticket_category::all() as $i) {
                $ticket_category[$i->name] = Ticket::where('category_id', $i->id)->count();
            }


            //get ticket with date
            $ticket_date_open = [];
            $ticket_date_close = [];

            for ($i = 1; $i <= 7; $i++) {
                $date = Carbon::now()->subDays($i)->StartOfDay();
                $sub_date = Carbon::now()->subDays($i - 1)->StartOfDay();
                $date_string = $date->format('Y-m-d');
                array_push(
                    $ticket_date_open,
                    array(
                        $date_string => Ticket::where('created_at', '>=', $date)->where('created_at', '<', $sub_date)->count(),
                    )
                );
                array_push(
                    $ticket_date_close,
                    array(
                        $date_string => Ticket::where('status_id', 5)->where('updated_at', '>=', $date)->where('updated_at', '<', $sub_date)->count(),
                    )
                );
            }
            $total = Ticket::where('rating', '>', '0')->count();
            $ticket_rating = [];
            array_push($ticket_rating, array("1" => (Ticket::where('rating', 1)->count() / $total) * 100));
            array_push($ticket_rating, array("2" => (Ticket::where('rating', 2)->count() / $total) * 100));
            array_push($ticket_rating, array("3" => (Ticket::where('rating', 3)->count() / $total) * 100));




            return response()->json(['users' => $users, 'tickets' => $tickets, 'ticket_status' => $ticket_status, 'ticket_category' => $ticket_category, 'ticket_open_date' => $ticket_date_open, 'ticket_close_date' => $ticket_date_close, 'ticket_rating' => $ticket_rating], 200);
        } else {
            return response()->json(['error' => 'Verfiy api token'], 403);
        }
    }
}
