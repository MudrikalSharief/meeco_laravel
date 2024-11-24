<?php

use App\Http\Controllers\AUTHcontroller;
use Illuminate\Support\Facades\Route;

Route::view('/','posts.index')->name('loggedin');

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [AUTHcontroller::class, 'register_user']);

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AUTHcontroller::class, 'login_user']);

Route::post('/logout', [AUTHcontroller::class, 'logout_user'])->name('logout');

Route::view('/capture', 'posts.capture')->name('capture');
Route::view('/subject', 'posts.subject')->name('subject');
Route::view('/deleted', 'posts.delete')->name('deleted');
Route::view('/upgrade', 'posts.upgrade')->name('upgrade');
Route::view('/profile', 'posts.profile')->name('profile');