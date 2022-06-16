<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\RoleController;

Route::get('/dashboard/roles', [RoleController::class, 'index'])->middleware(['auth'])->name('roles.index');
Route::get('/dashboard/roles/{role}', [RoleController::class, 'show'])->middleware(['auth'])->name('roles.show');
Route::get('/dashboard/roles/{role}/edit', [RoleController::class, 'edit'])->middleware(['auth'])->name('roles.edit');
Route::delete('/dashboard/roles/{role}', [RoleController::class, 'destroy'])->middleware(['auth'])->name('roles.destroy');
Route::post('/dashboard/roles/{role}/addPermission/', [RoleController::class, 'addPermission'])->middleware(['auth'])->name('roles.addPermission');
Route::delete('/dashboard/roles/{role}/removePermission/', [RoleController::class, 'removePermission'])->middleware(['auth'])->name('roles.removePermission');