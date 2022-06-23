<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Dashboard\DashboardDataController;

Route::get('/dashboard', function() {
    if(auth()->user()->hasPerm('read-data')) {
        return redirect('/dashboard/data');
    }elseif(auth()->user()->hasPerm('read-user')) {
        return redirect('/dashboard/users');
    }elseif(auth()->user()->hasPerm('read-role')) {
        return redirect('/dashboard/roles');
    }else {
        return redirect('/403');
    }
})->middleware(['auth'])->name('dashboard');
Route::get('/dashboard/data', [DashboardDataController::class, 'index'])->middleware(['auth'])->middleware(['auth',"perm:read-data"])->name('data.index');