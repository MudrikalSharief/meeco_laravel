<?php

use App\Http\Controllers\OPENAIController;   
use App\Http\Controllers\AUTHcontroller;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\IMAGEcontroller;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TOPICcontroller;
use App\Http\Controllers\ReviewerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RawController;



//these routes are only accecibble in authenticated or logged in users
Route::middleware('auth')->group(function (){
    Route::view('/','posts.capture')->name('loggedin');
    
    Route::view('/openai', 'openai.test')->name('test');
    Route::post('/openai/chat', [OPENAIController::class, 'handleChat']);
    
    Route::get('/js/openai.js', function () {
        return response()->file(resource_path('js/openai.js'));
    })->name('openai.js');
    //=====
    
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

    
    
    Route::post('/get-raw-text', [RawController::class, 'getRawText']);
    Route::post('/UpdateAndGet_RawText', [RawController::class, 'UpdateAndGet_RawText']);
    Route::post('/extract-text', [RawController::class, 'extractText']);
    Route::post('/store-extracted-text', [RawController::class, 'storeExtractedText']);
    
    Route::post('/generate-reviewer', [RawController::class, 'generateReviewer']);
    Route::post('/storeReviewer', [ReviewerController::class, 'storeReviewer'])->name('storeReviewer');
  
    Route::view('/reviewer', 'posts.reviewer')->name('reviewer');
    Route::post('/disect_reviewer', [ReviewerController::class, 'disectReviewer'])->name('disectReviewer');
    Route::get('/reviewer/{topicId}', [ReviewerController::class, 'showReviewPage'])->name('reviewer.show');
    Route::get('/generate-quiz/{topicId}',[OPENAIController::class,'generate_quiz'])->name('generate.quiz');


    //for quiz
    Route::view('/quiz', 'posts.quiz')->name('quiz');
});

Route::middleware('guest')->group(function (){
    
    Route::view('/', 'website.landing')->name('landing');
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AUTHcontroller::class, 'register_user']);
    
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AUTHcontroller::class, 'login_user']);

    Route::view('/website', 'website.landing')->name('landing');
    Route::view('/faq', 'website.faq')->name('faq');
    Route::view('/info', 'website.info_digest')->name('info_digest');
    Route::view('/quiz_maker', 'website.quiz_maker')->name('quiz_maker');
    Route::view('/convert_image', 'website.convert_image')->name('convert_image');
    Route::view('/summarizer_and_reviewer', 'website.summarizer_and_reviewer')->name('summarizer_and_reviewer');

    //footer

    Route::view('/terms', 'website.footer.terms')->name('terms');
});

Route::view('/upgrade/payment', 'subcriptionFolder.payment')->name('upgrade.payment');
Route::view('/upgrade/payment/paymentEmail', 'subcriptionFolder.paymentEmail')->name('upgrade.paymentEmail');
Route::view('/upgrade/payment/paymentEmail/gcashNumber', 'subcriptionFolder.gcashNumber')->name('upgrade.gcashNumber');
Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication', 'subcriptionFolder.authentication')->name('upgrade.authentication');
Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin', 'subcriptionFolder.mpin')->name('upgrade.mpin');
Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin/payment1', 'subcriptionFolder.payment1')->name('upgrade.payment1');
Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin/payment1/receipt', 'subcriptionFolder.receipt')->name('upgrade.receipt');
  
//admin routes
Route::middleware(['auth:admin'])->group(function () {
    Route::view('/admin-dashboard', 'admin.admin_view')->name('admin.dashboard');
    Route::view('/admin/users', 'admin.admin_users')->name('admin.users');
    Route::view('/admin/transactions', 'admin.admin_transactions')->name('admin.transactions');
    Route::view('/admin/statistics', 'admin.admin_statistics')->name('admin.statistics');
    Route::view('/admin/subscription', 'admin.admin_subscription')->name('admin.subscription');
    Route::view('/admin/account', 'admin.admin_account')->name('admin.account');
    Route::view('/admin/support', 'admin.admin_support')->name('admin.support');
    Route::view('/admin/logs', 'admin.admin_logs')->name('admin.logs');
    Route::view('/admin/settings', 'admin.admin_settings')->name('admin.settings');
    Route::get('/admin/manage_admin', [AUTHadminController::class, 'index'])->name('admin.admin-manage');
});

// Auth admin
Route::view('/admin', 'auth.login-admin')->name('admin.login');
Route::view('/admin-register', 'auth.register-admin')->name('admin.register');
Route::post('/admin-register', [AUTHcontroller::class, 'register_admin']);
Route::view('/admin-login', 'auth.login-admin')->name('admin.login');
Route::post('/admin-login', [AUTHcontroller::class, 'login_admin']);

// Admin Authentication Routes
Route::get('admin/login', [AUTHadminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AUTHadminController::class, 'login_admin']);
Route::get('admin/register', [AUTHadminController::class, 'showRegisterForm'])->name('admin.register');
Route::post('admin/register', [AUTHadminController::class, 'register_admin']);
Route::post('admin/logout', [AUTHadminController::class, 'logout_admin'])->name('admin.logout');

// Middleware for redirection based on authentication status
Route::middleware(['auth:admin'])->group(function () {
    Route::view('/admin/dashboard', 'admin.admin_view')->name('admin.dashboard');
    Route::view('/admin/users', 'admin.admin_users')->name('admin.users');
    Route::view('/admin/transactions', 'admin.admin_transactions')->name('admin.transactions');
    Route::view('/admin/statistics', 'admin.admin_statistics')->name('admin.statistics');
    Route::view('/admin/subscription', 'admin.admin_subscription')->name('admin.subscription');
    Route::view('/admin/account', 'admin.admin_account')->name('admin.account');
    Route::view('/admin/support', 'admin.admin_support')->name('admin.support');
    Route::view('/admin/logs', 'admin.admin_logs')->name('admin.logs');
    Route::view('/admin/settings', 'admin.admin_settings')->name('admin.settings');
    
    Route::view('/admin/manage_admin', 'admin.admin_manage')->name('admin.admin-manage');
    Route::get('/admin/manage_admin', [AUTHadminController::class, 'index'])->name('admin.admin-manage');
});

// Redirect to admin login if not authenticated
Route::middleware(['auth:admin/login'])->group(function () {
    Route::get('/admin/{any}', function () {
        return redirect()->route('admin.login');
    })->where('any', '.*');
});



