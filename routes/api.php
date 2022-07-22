<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DASHBOARD\DashboardDataController;
use App\Http\Controllers\API\ROLE\RoleController;
use App\Http\Controllers\API\TICKETS\TicketContentController;
use App\Http\Controllers\API\TICKETS\TicketController;
use App\Http\Controllers\API\USER\UserController;
use Illuminate\Support\Facades\Route;

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
Route::post('/ticket', [TicketController::class, 'store']);
//update ticket
Route::put('/ticket/{id}', [TicketController::class, 'update']);
//delete ticket
Route::delete('/ticket/{id}', [TicketController::class, 'destroy']);

// -------- TICKET CONTENT -------- //
//get all ticket content from ticket id
Route::get('/ticket/{ticketId}/content', [TicketContentController::class, 'index']);
//create ticket content
Route::post('/ticket/{ticketId}/content', [TicketContentController::class, 'store']);
//delete ticket content
Route::delete('/ticket/{ticketId}/content/{contentId}', [TicketContentController::class, 'destroy']);

// -------- DASHBOARD -------- //
//get all dashboard data
Route::get('/dashboard', [DashboardDataController::class, 'getAllData']);
//get all users
Route::get('/dashboard/users', [UserController::class, 'index']);
//get one user
Route::get('/dashboard/user/{id}', [UserController::class, 'show']);
//delete user
Route::delete('/dashboard/users/{id}', [UserController::class, 'destroy']);
//update user
Route::put('/dashboard/user', [UserController::class, 'update']);
Route::put('/dashboard/user/{UserId}/role/{RoleId}', [UserController::class, 'addRole']);

//roles

Route::get('/dashboard/roles', [RoleController::class, 'index']);
Route::get('/dashboard/role/{RoleId}', [RoleController::class, 'show']);
Route::post('/dashboard/role/{RoleId}', [RoleController::class, 'addPermission']);
Route::delete('/dashboard/role/{RoleId}/perm/{PermissionId}', [RoleController::class, 'deletePermission']);
Route::delete('/dashboard/role/{RoleId}', [RoleController::class, 'destroy']);

//verify token

Route::post('/verify_token', [AuthController::class, 'verify_tokenAPI']);
