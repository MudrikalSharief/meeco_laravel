<?php

use App\Http\Controllers\AUTHcontroller;
use Illuminate\Support\Facades\Route;

Route::view('/','posts.index')->name('loggedin');

Route::view('/register', 'auth.register')->name('register');

Route::post('/register', [AUTHcontroller::class, 'register_user']);
