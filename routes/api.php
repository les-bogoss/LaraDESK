<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TICKETS\TicketController;
use App\Http\Controllers\API\TICKETS\TicketContentController;


// -------- Auth -------- //
//register route
Route::post('/register', [AuthController::class, 'create']);
//login route
Route::post('/login', [AuthController::class, 'index']);
//logout route
Route::post('/logout', [AuthController::class, 'destroy']);

// -------- TICKETS -------- //
//get all tickets
Route::get('/tickets', [TicketController::class, 'index']);
//get single ticket
Route::get('/ticket/{id}', [TicketController::class, 'show']);
//create ticket
Route::post('/ticket',     [TicketController::class, 'store']);
//update ticket
Route::put('/ticket/{id}', [TicketController::class, 'update']);
//delete ticket
Route::delete('/ticket/{id}', [TicketController::class, 'destroy']);

// -------- TICKET CONTENT -------- //
//get all ticket content from ticket id
Route::get('/ticket/{ticketId}/content', [TicketContentController::class, 'index']);
//create ticket content
Route::post('/ticket/{ticketId}/content',     [TicketContentController::class, 'store']);
//delete ticket content
Route::delete('/ticket/{ticketId}/content/{contentId}', [TicketContentController::class, 'destroy']);
