<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;

Route::get('/dashboard/users', [UserController::class, 'index'])->middleware(['auth'])->name('users.index');
Route::get('/dashboard/users/{user}', [UserController::class, 'show'])->middleware(['auth'])->name('users.show');
Route::get('/dashboard/users/{user}/edit', [UserController::class, 'edit'])->middleware(['auth'])->name('users.edit');
Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy'])->middleware(['auth'])->name('users.destroy');
Route::post('/dashboard/users/{user}/addrole/', [UserController::class, 'addRole'])->middleware(['auth'])->name('users.addRole');
Route::delete('/dashboard/users/{user}/removeRole/', [UserController::class, 'removeRole'])->middleware(['auth'])->name('users.removeRole');