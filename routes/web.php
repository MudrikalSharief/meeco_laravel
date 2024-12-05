<?php

use App\Http\Controllers\AIcontroller;
use App\Http\Controllers\AUTHcontroller;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\IMAGEcontroller;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TOPICcontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RawController;


use App\Http\Controllers\ReviewController;



Route::post('/openai/test', [AIcontroller::class, 'test'])->name('openai.test');


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
    Route::post('/subjects/delete', [SubjectController::class, 'deleteSubject'])->name('subjects.delete');
    
    Route::get('/topics', [TOPICcontroller::class, 'getTopics'])->name('topics');
    Route::post('/topics/add', [TOPICcontroller::class, 'createTopic'])->name('topics.add');
    Route::get('/topics/subject/{subjectId}', [TopicController::class, 'getTopicsBySubject'])->name('topics.bySubject');
    Route::post('/topics/delete', [TOPICcontroller::class, 'deleteTopic'])->name('topics.delete');

    Route::view('/deleted', 'posts.delete')->name('deleted');
    Route::view('/upgrade', 'subcriptionFolder.upgrade')->name('upgrade');
    Route::view('/profile', 'components.profile')->name('profile');
    Route::view('/profile/cancelled', 'components.cancelled')->name('profile.cancelled');
    Route::view('/capture/extracted', 'posts.extracted')->name('capture.extracted');
  
    Route::post('/capture/extract', [CaptureController::class, 'extractText'])->name('capture.extract');

    Route::view('/openai/user_input', 'openai.user_input')->name('openai.user_input');
    Route::post('/openai/output', function () {
        return view('openai.output');
    })->name('openai.output');
    Route::view('/openai/quiz', 'openai.quiz')->name('openai.quiz');
    Route::view('/openai/score', 'openai.score')->name('openai.score');
    
    Route::post('/get-raw-text', [RawController::class, 'getRawText']);
    Route::post('/extract-text', [RawController::class, 'extractText']);
    Route::post('/store-extracted-text', [RawController::class, 'storeExtractedText']);
    
    Route::post('/generate-reviewer', [RawController::class, 'generateReviewer']);
  
    Route::view('/reviewer', 'posts.reviewer')->name('reviewer');
});

Route::view('/upgrade/payment', 'subcriptionFolder.payment')->name('upgrade.payment');
    Route::view('/upgrade/payment/paymentEmail', 'subcriptionFolder.paymentEmail')->name('upgrade.paymentEmail');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber', 'subcriptionFolder.gcashNumber')->name('upgrade.gcashNumber');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication', 'subcriptionFolder.authentication')->name('upgrade.authentication');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin', 'subcriptionFolder.mpin')->name('upgrade.mpin');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin/payment1', 'subcriptionFolder.payment1')->name('upgrade.payment1');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin/payment1/receipt', 'subcriptionFolder.receipt')->name('upgrade.receipt');
    Route::view('/', 'components.layout')->name('home');