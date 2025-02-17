<?php

use App\Http\Controllers\OPENAIController;   
use App\Http\Controllers\AUTHController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\IMAGEcontroller;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ReviewerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RawController;
use App\Http\Controllers\AUTHadminController;
use App\Http\Controllers\ADMINController;
use App\Http\Controllers\TransactionController;



Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('capture');
    } else {
        return redirect()->route('landing');
    }
});

//these routes are only accecibble in authenticated or logged in users
Route::middleware('auth')->group(function (){
    
    Route::view('/openai', 'openai.test')->name('test');
    Route::post('/openai/chat', [OPENAIController::class, 'handleChat']);
    
    Route::get('/js/openai.js', function () {
        return response()->file(resource_path('js/openai.js'));
    })->name('openai.js');
    //=====
    
    Route::post('/logout', [AUTHcontroller::class, 'logout_user'])->name('logout');

    Route::view('/capture', 'posts.capture')->name('capture');
    Route::post('/capture/upload', [ImageController::class, 'upload'])->name('capture.upload');
    Route::get('/capture/images', [ImageController::class, 'getUploadedImages'])->name('capture.images');
    Route::post('/capture/delete', [ImageController::class, 'deleteImage'])->name('capture.delete');

    Route::view('/subject', 'posts.subject')->name('subject');
    Route::get('/subjects', [SubjectController::class, 'getSubjects'])->name('subjects.list');
    Route::get('/subjects/{subjectName}', [TopicController::class, 'getTopicsBySubjectName'])->name('subjects');
    Route::post('/subjects/add', [SubjectController::class, 'createSubject'])->name('subjects.add');
    Route::post('/subjects/delete', [SubjectController::class, 'deleteSubject'])->name('subjects.delete');
    
    Route::get('/topics', [TopicController::class, 'getTopics'])->name('topics');
    Route::post('/topics/add', [TopicController::class, 'createTopic'])->name('topics.add');
    Route::get('/topics/subject/{subjectId}', [TopicController::class, 'getTopicsBySubject'])->name('topics.bySubject');
    Route::post('/topics/delete', [TopicController::class, 'deleteTopic'])->name('topics.delete');

    Route::view('/deleted', 'posts.delete')->name('deleted');
    Route::view('/upgrade', 'subscriptionFolder.upgrade')->name('upgrade');
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
    Route::post('/generate-quiz/{topicId}',[OPENAIController::class,'generate_quiz'])->name('generate.quiz');

    Route::get('/getquizzes',[QuizController::class,'getAllQuiz'])->name('get.quizzes');

    //for quiz
    Route::view('/quiz', 'posts.quiz')->name('quiz');
});

Route::middleware('guest')->group(function (){
    
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
    Route::view('/privacy', 'website.footer.privacy')->name('privacy');

});

Route::middleware(['auth:admin'])->group(function () {
    Route::view('/admin-dashboard', 'admin.admin_view')->name('admin.dashboard');
    Route::view('/admin/users', 'admin.admin_users')->name('admin.users');
    Route::view('/admin/statistics', 'admin.admin_statistics')->name('admin.statistics');
    Route::get('/admin/subscription', [PromoController::class, 'index'])->name('admin.subscription');
    Route::post('/subscriptions/store', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    Route::patch('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::view('/admin/account', 'admin.admin_account')->name('admin.account');
    Route::view('/admin/support', 'admin.admin_support')->name('admin.support');
    Route::view('/admin/logs', 'admin.admin_logs')->name('admin.logs');
    Route::view('/admin/settings', 'admin.admin_settings')->name('admin.settings');

    // Routes for promo actions
    Route::resource('promos', PromoController::class);
    Route::post('/promos', [PromoController::class, 'store'])->name('promos.store');
    Route::post('/promos/store', [PromoController::class, 'store'])->name('promos.store');
    Route::get('admin/promo', [PromoController::class, 'index'])->name('admin.promo.index');
    Route::get('admin/promo/create', [PromoController::class, 'create'])->name('admin.promo.create');
    Route::post('admin/promo', [PromoController::class, 'store'])->name('admin.promo.store');
    Route::get('admin/promo/{promo}/edit', [PromoController::class, 'edit'])->name('admin.promo.edit');
    Route::put('admin/promo/{promo}', [PromoController::class, 'update'])->name('admin.promo.update');
    Route::delete('admin.promo/{promo}', [PromoController::class, 'destroy'])->name('admin.promo.destroy');

    // New route for adding a promo
    Route::view('/admin/add-promo', 'admin.admin_addPromo')->name('admin.addPromo');
    Route::get('/admin/add-promo/{promo?}', [PromoController::class, 'createOrEdit'])->name('admin.addPromo');
    // Route for subscription view
    Route::get('/admin/subscription', [PromoController::class, 'index'])->name('admin.subscription');
    Route::post('admin/logout', [AUTHadminController::class, 'logout_admin'])->name('admin.logout');
});

// Auth admin
Route::view('/admin', 'auth.login-admin')->name('admin.login');
Route::view('/admin-register', 'auth.register-admin')->name('admin.register');
Route::post('/admin-register', [AUTHController::class, 'register_admin']);
Route::view('/admin-login', 'auth.login-admin')->name('admin.login');
Route::post('/admin-login', [AUTHController::class, 'login_admin']);


// Admin Authentication Routes
Route::get('admin/login', [AUTHadminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AUTHadminController::class, 'login_admin']);
Route::get('admin/register', [AUTHadminController::class, 'showRegisterForm'])->name('admin.register');
Route::post('admin/register', [AUTHadminController::class, 'register_admin']);
Route::post('admin/logout', [AUTHadminController::class, 'logout_admin'])->name('admin.logout');

//Transaction Routes
Route::get('admin/transactions', [TransactionController::class, 'get_transactions'])->name('admin.transactions');
Route::post('admin/filter-transaction', [TransactionController::class, 'filter_transactions'])->name('admin.filter-transactions');



