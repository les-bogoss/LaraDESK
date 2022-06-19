<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\TicketController;

Route::get('/tickets',  [TicketController::class, 'index'])->middleware(['auth','perm:read-ticket'])->name('tickets.index');
Route::post('/tickets',  [TicketController::class, 'store'])->middleware(['auth','perm:create-ticket'])->name('tickets.store');
Route::get('/tickets/{ticket}',  [TicketController::class, 'show'])->middleware(['auth','perm:read-ticket'])->name('tickets.show');
Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->middleware(['auth',"perm:update-ticket"])->name('tickets.edit');
Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->middleware(['auth','perm:delete-ticket'])->name('tickets.destroy');
Route::put('/tickets/{ticket}/editStatus/', [TicketController::class, 'editStatus'])->middleware(['auth','perm:update-ticket'])->name('tickets.editStatus');
Route::put('/tickets/{ticket}/editPriority/', [TicketController::class, 'editPriority'])->middleware(['auth','perm:update-ticket'])->name('tickets.editPriority');
Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->middleware(['auth','perm:update-ticket'])->name('tickets.assign');
Route::post('/tickets/{ticket}/content', [TicketController::class, 'createContent'])->middleware(['auth','perm:update-ticket'])->name('tickets.createContent');
