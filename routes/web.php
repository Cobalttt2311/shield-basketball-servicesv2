<?php

use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testmail', function () {
    Mail::raw('Test email dari Laravel + Mailtrap', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Mailtrap');
    });

    return 'Email terkirim (cek Mailtrap inbox)';
});

Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])
    ->name('password.request');

Route::post('/forgot-password', [UserController::class, 'forgotPassword'])
    ->name('password.email');

Route::get('/reset-password', [UserController::class, 'showResetPasswordForm'])
    ->name('password.reset.form');

Route::post('/reset-password', [UserController::class, 'resetPassword'])
    ->name('password.reset.submit');
