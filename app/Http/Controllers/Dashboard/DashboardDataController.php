<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket_category;
use App\Models\Ticket_status;
use App\Models\User;

class DashboardDataController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        $ticket_categories = Ticket_category::all();
        $ticket_statuses = Ticket_status::all();
        $users = User::all();
        return view('dashboard.data', compact('tickets', 'ticket_categories', 'ticket_statuses', 'users'));
    }
}
