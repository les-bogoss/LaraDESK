<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

URL::forceScheme('https');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/doc', function () {
    return view('doc');
})->name('doc');

Route::get('404', function () {
    return view('404');
})->name('not-found');

Route::get('403', function () {
    return view('403');
})->name('forbidden');

require __DIR__.'/auth.php';
require __DIR__.'/dashboard/data.php';
require __DIR__.'/dashboard/roles.php';
require __DIR__.'/dashboard/users.php';
require __DIR__.'/tickets.php';
require __DIR__.'/account.php';

// Si aucune route n'est trouvée, on redirige vers la page 404
Route::fallback(function () {
    return redirect()->route('not-found');
});
