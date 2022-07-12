<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/account', [AccountController::class, 'index'])->middleware(['auth', 'verified'])->name('account.index');
Route::post('/account/pfp', [AccountController::class, 'updateProfilePicture'])->middleware(['auth'])->name('account.updateProfilePicture');
