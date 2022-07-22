<?php

use App\Http\Controllers\Dashboard\DashboardDataController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    if (auth()->user()->hasPerm('read-data')) {
        return redirect('/dashboard/data');
    } elseif (auth()->user()->hasPerm('read-user')) {
        return redirect('/dashboard/users');
    } elseif (auth()->user()->hasPerm('read-role')) {
        return redirect('/dashboard/roles');
    } else {
        return redirect('/403');
    }
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/data', [DashboardDataController::class, 'index'])->middleware(['auth', 'verified'])->middleware(['auth', 'perm:read-data'])->name('data.index');
Route::get('/dashboard/data/extract', [DashboardDataController::class, 'extract'])->middleware(['auth', 'verified'])->middleware(['auth', 'perm:read-data'])->name('data.extract');
