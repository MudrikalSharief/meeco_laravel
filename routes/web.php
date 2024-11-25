<?php

use App\Http\Controllers\AUTHcontroller;
use App\Http\Controllers\IMAGEcontroller;
use Illuminate\Support\Facades\Route;

Route::view('/','posts.index')->name('loggedin');

Route::middleware('guest')->group(function (){

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AUTHcontroller::class, 'register_user']);
    
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AUTHcontroller::class, 'login_user']);

    Route::view('/website', 'website.landing')->name('landing');
    Route::view('/faq', 'website.faq')->name('faq');

});

Route::middleware('auth')->group(function (){
    Route::post('/logout', [AUTHcontroller::class, 'logout_user'])->name('logout');

    Route::view('/capture', 'posts.capture')->name('capture');
    Route::post('/capture/upload', [ImageController::class, 'upload'])->name('capture.upload');
    Route::get('/capture/images', [ImageController::class, 'getUploadedImages'])->name('capture.images');

    Route::view('/subject', 'posts.subject')->name('subject');
    Route::view('/deleted', 'posts.delete')->name('deleted');
    Route::view('/upgrade', 'posts.upgrade')->name('upgrade');
    Route::view('/profile', 'posts.profile')->name('profile');
});