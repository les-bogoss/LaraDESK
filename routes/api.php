<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TICKETS\TicketController;


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
//get ticket
Route::get('/ticket/{id}', [TicketController::class, 'show']);
//create ticket
Route::post('/ticket',     [TicketController::class, 'store']);
//update ticket
Route::put('/ticket/{id}', [TicketController::class, 'update']);
//delete ticket
Route::delete('/ticket/{id}', [TicketController::class, 'destroy']);

// -------- TICKET CONTENT -------- //
//get ticket content
Route::get('/ticket/{id}/content', [TicketController::class, 'show_content']);
//create ticket content
Route::post('/ticket/{id}/content',     [TicketController::class, 'add_content']);
//delete ticket content
Route::delete('/ticket/{id}/content/{contentId}', [TicketController::class, 'delete_content']);
