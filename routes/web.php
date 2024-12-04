<?php

use App\Http\Controllers\AUTHcontroller;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\IMAGEcontroller;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TOPICcontroller;
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
    Route::view('/','posts.capture')->name('loggedin');
    
    Route::post('/logout', [AUTHcontroller::class, 'logout_user'])->name('logout');

    Route::view('/capture', 'posts.capture')->name('capture');
    Route::post('/capture/upload', [IMAGEcontroller::class, 'upload'])->name('capture.upload');
    Route::get('/capture/images', [ImageController::class, 'getUploadedImages'])->name('capture.images');
    Route::post('/capture/delete', [IMAGEcontroller::class, 'deleteImage'])->name('capture.delete');

    Route::view('/subject', 'posts.subject')->name('subject');
    Route::get('/subjects', [SubjectController::class, 'getSubjects'])->name('subjects.list');
    Route::get('/subjects/{subjectName}', [TOPICcontroller::class, 'getTopicsBySubjectName'])->name('subjects');
    Route::post('/subjects/add', [SubjectController::class, 'createSubject'])->name('subjects.add');
    
    Route::get('/topics', [TOPICcontroller::class, 'getTopics'])->name('topics');
    Route::post('/topics/add', [TOPICcontroller::class, 'createTopic'])->name('topics.add');
    Route::get('/topics/subject/{subjectId}', [TopicController::class, 'getTopicsBySubject'])->name('topics.bySubject');

    Route::view('/deleted', 'posts.delete')->name('deleted');
    Route::view('/upgrade', 'posts.upgrade')->name('upgrade');
    Route::view('/profile', 'posts.profile')->name('profile');

  
    Route::post('/capture/extract', [CaptureController::class, 'extractText'])->name('capture.extract');
});