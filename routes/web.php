<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

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