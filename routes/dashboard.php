<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Dashboard\DashboardDataController;
use \App\Http\Controllers\Dashboard\DashboardUsersController;
use \App\Http\Controllers\UserController;

Route::get('/dashboard', [DashboardDataController::class, 'index'])->middleware(['auth'])->name('dashboardData.index');
Route::get('/dashboard/data', [DashboardDataController::class, 'index'])->middleware(['auth'])->name('dashboardData.index');

Route::get('/dashboard/users', [DashboardUsersController::class, 'index'])->middleware(['auth'])->name('dashboardUsers.index');
Route::get('/dashboard/users/{user}', [DashboardUsersController::class, 'show'])->middleware(['auth'])->name('dashboardUsers.show');

Route::post('/dashboard/users/{user}/addrole/', [UserController::class, 'addRole'])->middleware(['auth'])->name('User.addRole');
Route::delete('/dashboard/users/{user}/removeRole/', [UserController::class, 'removeRole'])->middleware(['auth'])->name('User.removeRole');