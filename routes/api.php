<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TICKETS\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//register route
Route::post('/register',[AuthController::class, 'create']);
//login route
Route::post('/login', [AuthController::class, 'index']);
//logout route
Route::post('/logout', [AuthController::class, 'destroy']);
//get all tickets
Route::get('/tickets', [TicketController::class, 'index']);