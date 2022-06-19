<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Dashboard\DashboardDataController;

Route::get('/dashboard', [DashboardDataController::class, 'index'])->middleware(['auth'])->name('dashboardData.index');
Route::get('/dashboard/data', [DashboardDataController::class, 'index'])->middleware(['auth'])->name('dashboardData.index');