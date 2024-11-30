<?php

use App\Http\Controllers\AUTHcontroller;
use App\Http\Controllers\IMAGEcontroller;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function (){

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AUTHcontroller::class, 'register_user']);
    
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AUTHcontroller::class, 'login_user']);

    Route::view('/website', 'website.landing')->name('landing');
    Route::view('/faq', 'website.faq')->name('faq');
    Route::view('/quiz_maker', 'website.quiz_maker')->name('quiz_maker');
    Route::view('/convert_image', 'website.convert_image')->name('convert_image');
    Route::view('/summarizer_and_reviewer', 'website.summarizer_and_reviewer')->name('summarizer_and_reviewer');

});

Route::middleware('auth')->group(function (){
    Route::view('/','posts.index')->name('loggedin');
    
    Route::post('/logout', [AUTHcontroller::class, 'logout_user'])->name('logout');

    Route::view('/capture', 'posts.capture')->name('capture');
    Route::post('/capture/upload', [ImageController::class, 'upload'])->name('capture.upload');
    Route::get('/capture/images', [ImageController::class, 'getUploadedImages'])->name('capture.images');

    Route::view('/subject', 'posts.subject')->name('subject');
    Route::view('/topics', 'posts.topics')->name('topics');


    Route::view('/deleted', 'posts.delete')->name('deleted');
    Route::view('/upgrade', 'posts.upgrade')->name('upgrade');
    Route::view('/profile', 'posts.profile')->name('profile');
});