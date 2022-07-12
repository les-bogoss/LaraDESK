<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'verified'], function () {
    Route::get('/dashboard/roles', [RoleController::class, 'index'])->middleware(['auth', 'perm:read-role'])->name('roles.index');
    Route::get('/dashboard/roles/{role}', [RoleController::class, 'show'])->middleware(['auth', 'perm:read-role'])->name('roles.show');
    Route::put('/dashboard/roles/{role}', [RoleController::class, 'update'])->middleware(['auth', 'perm:update-role'])->name('roles.update');
    Route::delete('/dashboard/roles/{role}', [RoleController::class, 'destroy'])->middleware(['auth', 'perm:delete-role'])->name('roles.destroy');
    Route::post('/dashboard/roles', [RoleController::class, 'store'])->middleware(['auth', 'perm:create-role'])->name('roles.store');
    Route::post('/dashboard/roles/{role}/addPermission/', [RoleController::class, 'addPermission'])->middleware(['auth', 'perm:update-role'])->name('roles.addPermission');
    Route::delete('/dashboard/roles/{role}/removePermission/', [RoleController::class, 'removePermission'])->middleware(['auth', 'perm:update-role'])->name('roles.removePermission');
});
