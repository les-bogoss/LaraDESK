<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AccountController;

Route::get('/account', [AccountController::class, 'index'] )->middleware(['auth','verified'])->name('account.index');
Route::post('/account/pfp', [AccountController::class, 'updateProfilePicture'] )->middleware(['auth'])->name('account.updateProfilePicture');
