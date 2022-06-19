<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AccountController;

Route::get('/account', [AccountController::class, 'index'] )->name('account.index');
Route::post('/account/pfp', [AccountController::class, 'updateProfilePicture'] )->name('account.updateProfilePicture');
