<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api', 'verified']], function () {
    Route::post('/register', [TicketController::class, 'create']);
    Route::get('/tickets', [TicketController::class, 'index'])->middleware(['auth'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->middleware(['auth', 'perm:create-ticket'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->middleware(['auth'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->middleware(['auth'])->name('tickets.edit');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->middleware(['auth'])->name('tickets.destroy');
    Route::put('/tickets/{ticket}/editStatus/', [TicketController::class, 'editStatus'])->middleware(['auth'])->name('tickets.editStatus');
    Route::put('/tickets/{ticket}/editPriority/', [TicketController::class, 'editPriority'])->middleware(['auth'])->name('tickets.editPriority');
    Route::put('/tickets/{ticket}/editRating/', [TicketController::class, 'editRating'])->middleware(['auth'])->name('tickets.editRating');
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->middleware(['auth'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/content', [TicketController::class, 'createContent'])->middleware(['auth'])->name('tickets.createContent');
    Route::delete('/tickets/{ticket}/content/{content}', [TicketController::class, 'deleteContent'])->middleware(['auth'])->name('tickets.deleteContent');
    Route::put('/tickets/{ticket}/technician', [TicketController::class, 'editTechnician'])->middleware(['auth'])->name('tickets.editTechnician');
});
