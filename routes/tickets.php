<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\TicketController;

Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->middleware(['auth'])->name('tickets.destroy');
