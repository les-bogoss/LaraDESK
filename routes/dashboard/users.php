<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'verified'], function () {
    Route::get('/dashboard/users', [UserController::class, 'index'])->middleware(['auth', 'perm:read-user'])->name('users.index');
    Route::get('/dashboard/users/{user}', [UserController::class, 'show'])->middleware(['auth', 'perm:read-user'])->name('users.show');
    Route::put('/dashboard/users/{user}', [UserController::class, 'update'])->middleware(['auth', 'perm:update-user'])->name('users.update');
    Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy'])->middleware(['auth', 'perm:delete-user'])->name('users.destroy');
    Route::post('/dashboard/users/{user}/addrole/', [UserController::class, 'addRole'])->middleware(['auth', 'perm:update-user'])->name('users.addRole');
    Route::delete('/dashboard/users/{user}/removeRole/', [UserController::class, 'removeRole'])->middleware(['auth', 'perm:update-user'])->name('users.removeRole');
});
