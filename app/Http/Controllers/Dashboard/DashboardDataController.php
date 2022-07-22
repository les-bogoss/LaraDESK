<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Ticket_category;
use App\Models\Ticket_status;
use App\Models\User;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

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

    public function extract()
    {
        $tickets = Ticket::all();
        $ticket_categories = Ticket_category::all();
        $ticket_statuses = Ticket_status::all();
        $users = User::all();

        $pdf = PDF::loadView('dashboard.data-extract', compact('tickets', 'ticket_categories', 'ticket_statuses', 'users'));
        return $pdf->stream();
    }
}
