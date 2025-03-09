<?php

use App\Http\Middleware\EmployeeMiddleware;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use App\Mail\TestMail;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([EmployeeMiddleware::class])
    ->group(function () {
    Route::get('/home', Home::class)->name('home');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::get('/test-email', function () {
    Mail::to('kavinthaoshada@gmail.com')->send(new TestMail());
    return 'Test email sent!';
});
