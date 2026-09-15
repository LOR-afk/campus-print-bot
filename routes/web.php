<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessengerWebhookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/webhook/messenger', [
    MessengerWebhookController::class,
    'verify',
]);

Route::post('/webhook/messenger', [
    MessengerWebhookController::class,
    'receive',
]);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/printers', function () {
        return view('printers');
    })->name('printers.index');
});
