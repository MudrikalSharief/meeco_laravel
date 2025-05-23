<?php

use App\Http\Controllers\OPENAIController;   
use App\Http\Controllers\AUTHController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\IMAGEcontroller;
use App\Http\Controllers\PayMongoController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ReviewerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RawController;
use App\Http\Controllers\AUTHadminController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\SettingsController;

use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ProfileController;

Route::view('/gemmatest', 'posts.GemmaTest')->name('gemmatest');
Route::get('/api/gemma3', [OPENAIController::class, 'gemma3']);

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
    // Add the new route for analyze-graph
    Route::post('/openai/analyze-graph', [OPENAIController::class, 'analyze_graph']);
    
    Route::get('/js/openai.js', function () {
        return response()->file(resource_path('js/openai.js'));
    })->name('openai.js');
    //=====
    
    Route::post('/logout', [AUTHcontroller::class, 'logout_user'])->name('logout');

    Route::view('/capture', 'posts.capture')->name('capture');
    Route::post('/capture/upload', [IMAGEcontroller::class, 'upload'])->name('capture.upload');
    Route::get('/capture/images', [IMAGEcontroller::class, 'getUploadedImages'])->name('capture.images');
    Route::post('/capture/delete', [IMAGEcontroller::class, 'deleteImage'])->name('capture.delete');
    Route::get('/capture/check', [IMAGEcontroller::class, 'checkimagesize'])->name('capture.image.check');
    Route::post('/capture/move-to-graph', [App\Http\Controllers\CaptureController::class, 'moveToGraph']);
    Route::post('/capture/move-to-container', [App\Http\Controllers\CaptureController::class, 'moveToContainer']);
    Route::get('/capture/container-images', [App\Http\Controllers\CaptureController::class, 'getContainerImages']);
    Route::get('/capture/graph-images', [App\Http\Controllers\CaptureController::class, 'getGraphImages']);

    Route::view('/subject', 'posts.subject')->name('subject');
    Route::get('/subjects', [SubjectController::class, 'getSubjects'])->name('subjects.list');
    Route::get('/subjects/{subjectName}', [TopicController::class, 'getTopicsBySubjectName'])->name('subjects');
    Route::post('/subjects/add', [SubjectController::class, 'createSubject'])->name('subjects.add');
    Route::post('/subjects/delete', [SubjectController::class, 'deleteSubject'])->name('subjects.delete');
    Route::post('/subjects/edit', [SubjectController::class, 'editSubject'])->name('subjects.edit');
    
    Route::get('/topics', [TopicController::class, 'getTopics'])->name('topics');
    Route::post('/topics/add', [TopicController::class, 'createTopic'])->name('topics.add');
    Route::get('/subject/topics/{subjectId}', [TopicController::class, 'getTopicsBySubject'])->name('topics.bySubject');
    Route::get('/topics/subject/{subjectId}', [TopicController::class, 'getTopicsBySubjectincapture'])->name(']Subject.bytopic');
    
    
    Route::post('/topics/delete', [TopicController::class, 'deleteTopic'])->name('topics.delete');
    Route::get('/getTopicByTopicId/{topicId}',[TopicController::class,'getTopicByTopicId'])->name('getTopicByTopicId');
    Route::post('/topics/edit', [TopicController::class, 'editTopic'])->name('topics.edit');

    Route::view('/deleted', 'posts.delete')->name('deleted');
    Route::view('/upgrade', 'subscriptionFolder.upgrade')->name('upgrade');
    Route::view('/profile', 'components.profile')->name('profile');
    Route::view('/capture/extracted', 'posts.extracted')->name('capture.extracted');
    Route::post('/capture/extract', [CaptureController::class, 'extractText'])->name('capture.extract');

    //for the reviewer list
    Route::get('/reviewers', [ReviewerController::class, 'getReviewerList'])->name('reviewers');
    Route::view('/reviewer_list', 'posts.reviewer_list')->name('reviewer.list');
    Route::get('/reviewers-by-subject', [ReviewerController::class, 'getReviewersGroupedBySubject'])->name('reviewers.by.subject');

    //for quizzes list
    Route::get('/quizzes', [ReviewerController::class, 'getQuizList'])->name('quizzes');
    Route::view('/quiz_list', 'posts.quiz_list')->name('quiz.list');
    Route::get('/quizzes-by-subject', [ReviewerController::class, 'getQuizzesGroupedBySubject'])->name('quizzes.by.subject');
    Route::get('/quizzes-by-topic', [ReviewerController::class, 'getQuizzesGroupedByTopic'])->name('quizzes.by.topic');

    //contact us
    Route::view('/contact', 'Contact.contact')->name('contact');
    Route::view('/contact/inquiry', 'Contact.inquiry')->name('inquiry');
    Route::get('/contact/inquiry-history', [ContactUsController::class, 'inquiryHistory'])->name('inquiry-history');
    Route::view('/contact/inquiry-history/second', 'Contact.inquiry_history2')->name('inquiry-history2');
    Route::post('/contact/inquiry', [ContactUsController::class, 'submitInquiry'])->name('submitInquiry');
    Route::get('/contact/inquiry-history/{ticket_reference}', [ContactUsController::class, 'getInquiryDetails'])->name('inquiry.details');
    Route::post('/contact/inquiry-history/{ticket_reference}/reply', [ContactUsController::class, 'submitReply'])->name('submitReply');
    Route::post('/contact/inquiry-history/{ticket_reference}/close', [ContactUsController::class, 'closeInquiry'])->name('closeInquiry');

    //for reviewer
    Route::post('/get-raw-text', [RawController::class, 'getRawText']);
    Route::post('/UpdateAndGet_RawText', [RawController::class, 'UpdateAndGet_RawText']);
    Route::post('/extract-text', [RawController::class, 'extractText']);
    Route::post('/store-extracted-text', [RawController::class, 'storeExtractedText']);
    Route::post('/generate-reviewer', [RawController::class, 'generateReviewer']);
    Route::post('/storeReviewer', [ReviewerController::class, 'storeReviewer'])->name('storeReviewer');
    Route::view('/reviewer', 'posts.reviewer')->name('reviewer');
    Route::post('/disect_reviewer', [ReviewerController::class, 'disectReviewer'])->name('disectReviewer');
    Route::get('/reviewer/{topicId}', [ReviewerController::class, 'showReviewPage'])->name('reviewer.show');
    Route::view('/cards','posts.cards')->name('card');

   //this is for download
   Route::post('/download-pdf', [ReviewerController::class, 'downloadPdf'])->name('downloadpdf');
   Route::view('posts.pdf_reviewer', 'posts.pdf_reviewer')->name('pdf.reviewer');
    // Route::post('/download-reviewer', [ReviewerController::class, 'downloadReviewer'])->name('download.reviewer');
    // Route::get('/serve-file/{fileName}', [ReviewerController::class, 'serveFile']);

    //this is for the checking of subcription
    Route::post('/subscription/check', [SubscriptionController::class, 'checkSubscription'])->name('subscription.check');
    Route::post('/subscription/get-quiz-question-limit', [SubscriptionController::class, 'getQuizQuestionLimit'])->name('subscription.getQuizQuestionLimit');


    //for quiz
    Route::post('/generate-quiz/{topicId}',[OPENAIController::class,'generate_quiz'])->name('generate.quiz');
    Route::get('/getquizzes/{topicId}',[QuizController::class,'getAllQuiz'])->name('get.quizzes');
    Route::get('/getquiz/{quizId}',[QuizController::class,'getQuiz'])->name('get.quiz');
    Route::get('/startquiz/{questionId}',[QuizController::class,'startQuiz'])->name('start.quiz');
    Route::get('/takequiz/{questionId}',[QuizController::class,'takeQuiz'])->name('take-quiz');
    Route::get('/getquizresult/{questionId}',[QuizController::class,'getQuizResult'])->name('get.quizresult');
    Route::post('/submitquiz',[QuizController::class,'submitQuiz'])->name('submit.quiz');
    Route::post('/resetquiz/{id}',[QuizController::class,'resetQuiz'])->name('reset.quiz');
    Route::view('/quiz', 'posts.quiz')->name('quiz');
    Route::view('/takequiz', 'posts.takequiz')->name('takequiz');
    Route::view('/quizresult', 'posts.quizresult')->name('quizresult');
    Route::delete('/deletequiz/{id}', [QuizController::class, 'deleteQuiz'])->name('delete.quiz');
    Route::post('/editquiz/{id}', [QuizController::class, 'editQuizName'])->name('edit.quiz');

    //route for paymonggo
    Route::post('/Paymongo', [PayMongoController::class, 'paymongoPayment'])->name('paymongo');
    Route::post('/checkpayment', [PayMongoController::class, 'retrieve_payment']);
    Route::view('/testpay', 'posts.paymongo')->name('testpay');

    Route::view('/upgrade/payment', 'subscriptionFolder.payment')->name('upgrade.payment');
    Route::get('/upgrade/paymentEmail/{promo_id}', [SubscriptionController::class, 'paymentEmail'])->name('upgrade.paymentEmail');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber', 'subscriptionFolder.gcashNumber')->name('upgrade.gcashNumber');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication', 'subscriptionFolder.authentication')->name('upgrade.authentication');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin', 'subscriptionFolder.mpin')->name('upgrade.mpin');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin/payment1', 'subscriptionFolder.payment1')->name('upgrade.payment1');
    Route::view('/upgrade/payment/paymentEmail/gcashNumber/authentication/mpin/payment1/receipt', 'subscriptionFolder.receipt')->name('upgrade.receipt');
    Route::get('/upgrade', [PromoController::class, 'showPromos'])->name('upgrade');
    Route::get('/upgrade/payment1/{promo_id}', [SubscriptionController::class, 'payment1'])->name('upgrade.payment3');
    Route::get('/upgrade/receipt/{promo_id}', [SubscriptionController::class, 'receipt'])->name('upgrade.receipt2');
    Route::get('/upgrade/payment/{promo_id}', [SubscriptionController::class, 'payment'])->name('upgrade.payment2');
    Route::get('/upgrade/paymentEmail/gcashNumber/{promo_id}', [SubscriptionController::class, 'gcashNumber'])->name('upgrade.gcashNumber2');
    Route::get('/upgrade/paymentEmail/gcashNumber/authentication/mpin/{promo_id}', [SubscriptionController::class, 'mpin'])->name('upgrade.mpin2');

    //profile
    Route::get('/profile/cancelled', function () {
        return view('profile.cancelled');
    })->name('profile.cancelled');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/upload', [ProfileController::class, 'uploadProfilePicture'])->name('profile.upload');
    Route::post('/profile/cancel-subscription', [ProfileController::class, 'cancelSubscription'])->name('profile.cancelSubscription');

});


 // UI only routes for password reset (no backend)
 Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
 Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');

 // Password reset routes
 Route::post('/forgot-password', [AUTHcontroller::class, 'forgotPassword'])->name('password.email');
 Route::post('/reset-password', [AUTHcontroller::class, 'resetPassword'])->name('password.update');

Route::middleware('guest')->group(function (){
    
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register-store', [AUTHcontroller::class, 'storeRegistration'])->name('register.store');
    
    // Verification routes
    Route::get('/verify-user', [AUTHcontroller::class, 'showVerificationForm'])->name('verify.show');
    Route::post('/verify-email', [AUTHcontroller::class, 'verifyEmail'])->name('verify.email');
    Route::post('/resend-code', [AUTHcontroller::class, 'resendVerificationCode'])->name('verify.resend');
    
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

    // For testing purposes - remove in production
    Route::get('/show-code', [AUTHcontroller::class, 'showCurrentCode'])->name('show.code');

});

// Remove this commented route since we've now implemented it
// Route::post('/register-store', [AUTHcontroller::class, 'storeRegistration'])->name('register.store');

Route::middleware(['auth:admin'])->group(function () {
    Route::view('/admin-dashboard', 'admin.admin_view')->name('admin.dashboard');
    Route::get('/admin/users', [AUTHadminController::class, 'showUsers'])->name('admin.users');
    Route::put('/admin/users/update', [AUTHadminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [AUTHadminController::class, 'deleteUser'])->name('admin.users.delete');
    // Route::view('/admin/statistics', 'admin.admin_statistics')->name('admin.statistics');
    Route::get('/admin/subscription', [PromoController::class, 'index'])->name('admin.subscription');
    Route::get('/gcash/{promo_id}', [SubscriptionController::class, 'gcashNumber'])->name('gcash.number');
    Route::post('/subscriptions/store', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    Route::patch('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::view('/admin/account', 'admin.admin_account')->name('admin.account');
    Route::view('/admin/support', 'admin.admin_support')->name('admin.support');
    Route::view('/admin/logs', 'admin.admin_logs')->name('admin.logs');
    Route::view('/admin/settings', 'admin.admin_settings')->name('admin.settings');
    Route::get('/admin/manage_admin', [AUTHadminController::class, 'index'])->name('admin.admin-manage');
    Route::post('/admin/admins/create', [AUTHadminController::class, 'createAdmin'])->name('admin.admins.create');
    Route::put('/admin/admins/update', [AUTHadminController::class, 'updateAdmin'])->name('admin.admins.update');
    Route::get('/admin/admins/{admin}/edit', [AUTHadminController::class, 'editAdmin'])->name('admin.admins.edit');
    Route::delete('/admin/admins/delete', [AUTHadminController::class, 'deleteAdmin'])->name('admin.admins.delete');

    // Routes for promo actions
    Route::resource('promos', PromoController::class);
    Route::post('/promos', [PromoController::class, 'store'])->name('promos');
    Route::post('/promos/store', [PromoController::class, 'store'])->name('promos.store');
    Route::get('admin/promo', [PromoController::class, 'index'])->name('admin.promo.index');
    Route::get('admin/promo/create', [PromoController::class, 'create'])->name('admin.promo.create');
    Route::post('admin/promo', [PromoController::class, 'store'])->name('admin.promo.store');
    Route::get('admin/promo/{promo}/edit', [PromoController::class, 'edit'])->name('admin.promo.edit');
    Route::put('admin.promo/{promo}', [PromoController::class, 'update'])->name('admin.promo.update');
    Route::delete('admin.promo/{promo}', [PromoController::class, 'destroy'])->name('admin.promo.destroy');

    // New route for adding a promo
    Route::view('/admin/add-promo', 'admin.admin_addPromo')->name('viewadmin.addPromo');
    Route::get('/admin/add-promo/{promo?}', [PromoController::class, 'createOrEdit'])->name('admin.addPromo');
    Route::get('/admin/editPromo/{promo}', [PromoController::class, 'createOrEdit'])->name('admin.editPromo');
    Route::delete('/admin/deletePromo/{promo}', [PromoController::class, 'destroy'])->name('admin.deletePromo');

    Route::post('admin/logout', [AUTHadminController::class, 'logout_admin'])->name('admin.logout');

    Route::get('/admin/users', [AUTHadminController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/users/{id}', [AUTHadminController::class, 'getUserById'])->name('admin.users.detail');
    Route::post('/admin/users/create', [AUTHadminController::class, 'createUser'])->name('admin.users.create');
    /* Route::delete('/admin/users/{email}', [AUTHadminController::class, 'deleteUserByEmail'])->name('admin.users.delete'); */
    
    Route::get('/admin/addPromo', [PromoController::class, 'createOrEdit'])->name('editadmin.addPromo');
    Route::get('/admin/editPromo/{promo}', [PromoController::class, 'createOrEdit'])->name('admin.editPromo');
    Route::post('/promos/store', [PromoController::class, 'store'])->name('promos.store');
    Route::delete('/admin/deletePromo/{promo}', [PromoController::class, 'destroy'])->name('admin.deletePromo');

    //Support Ticket Routes
    Route::get('/admin/support', [ContactUsController::class, 'SupportTicketAdmin'])->name('admin.support');
    Route::get('/admin/support/filter', [ContactUsController::class, 'filterInquiriesByStatus'])->name('filter.inquiries');
    Route::view('/admin/support/reply', 'admin.admin_supportReply')->name('viewadmin.reply');
    Route::get('/admin/support/reply/{ticket_reference}', [ContactUsController::class, 'getAdminInquiryDetails'])->name('admin.reply');
    Route::post('/admin/support/reply/{ticket_reference}', [ContactUsController::class, 'submitAdminReply'])->name('admin.submitReply');
   

    // Remove or comment out these redundant routes since they are now handled by ContactUsController
    // Route::get('/admin/support', [AdminController::class, 'support'])->name('admin.support');
    // Route::get('/admin/filter-inquiries', [AdminController::class, 'filterInquiries'])->name('filter.inquiries');

    // Make sure you have these routes defined:
    
    //Transaction ROute

    Route::view('admin/transactions', 'admin.admin_transactions')->name('admin.transactions');
    Route::get('admin/get-transactions', [TransactionController::class, 'get_transactions'])->name('admin.get-transactions');
    Route::post('admin/filter-transaction', [TransactionController::class, 'filter_transactions'])->name('admin.filter-transactions');
    Route::post('admin/sort-transaction', [TransactionController::class, 'sort_transactions'])->name('admin.sort-transactions');


    //New Transaction Route
    Route::get('admin/newtransactions', [SubscriptionController::class, 'getAllTransactions'])->name('admin.newtransactions');
    Route::get('/admin/newtransactions/inactive-transactions', [SubscriptionController::class, 'getInactiveTransactions'])->name('admin.inactive-transactions');
    Route::get('admin/subscription/{subscription}/edit-data', [SubscriptionController::class, 'getSubscriptionData']);
    Route::put('admin/subscription/{subscription}/update', [SubscriptionController::class, 'updateSubscription']);
    
    // Add Subscription Routes
    Route::get('/admin/newtransactions/create', [SubscriptionController::class, 'create'])->name('admin.subscription.create');
    Route::post('/admin/newtransactions/store', [SubscriptionController::class, 'storeAdminSubscription'])->name('admin.subscription.store');

    //Statistic Route
    Route::view('admin/statistics', 'admin.admin_statistics')->name('admin.statistics');
    Route::view('admin/finalsstatistics', 'admin.admin_statisticsFINALS')->name('admin.statistics-finals');

    Route::post('admin/get-weekly-statistics', [StatisticsControllerFINALS::class, 'get_weekly_statistics'])->name('admin.get-statistics');
    Route::post('admin/get-yearly-statistics', [StatisticsControllerFINALS::class, 'get_yearly_statistics'])->name('admin.get-statistics1');
    Route::post('admin/get-weekly-statistics', [StatisticsControllerFINALS::class, 'get_weekly_statistics'])->name('admin.get-statistics2');
    Route::post('admin/filter-yearly-stats', [StatisticsControllerFINALS::class, 'filter_yearly_statistics'])->name('admin.get-statistics3');
    Route::post('admin/filter-weekly-stats', [StatisticsControllerFINALS::class, 'filter_weekly_statistics'])->name('admin.get-statistics4');
    //New Statistic Route
    Route::view('admin/newstatistics', 'admin.admin_newstatistics')->name('admin.newstatistics');
    // Route::view('admin/newstatisticsfinals', 'admin.admin_newstatisticsFINALS')->name('admin.newstatisticsfinals');
    Route::get('admin/subscription-stats', [SubscriptionController::class, 'getSubscriptionStats'])->name('admin.subscription-stats');
    Route::get('admin/subscription-stats/monthly', [SubscriptionController::class, 'getMonthlyStats'])->name('admin.subscription-stats.monthly');

    Route::resource('admin-actions', AdminActionController::class);
    Route::get('/admin/logs', [AdminActionController::class, 'index'])->name('admin.logs');
    //Settings Route:
    Route::view('admin/settings', 'admin.admin_settings')->name('admin.settings');

    // Add these PDF generation routes
    Route::get('/admin/statistics/daily-pdf', [App\Http\Controllers\SubscriptionController::class, 'generateDailyStatsPdf'])
        ->name('admin.statistics.daily-pdf')
        ->middleware(['auth:admin']);

    Route::get('/admin/statistics/monthly-pdf', [App\Http\Controllers\SubscriptionController::class, 'generateMonthlyStatsPdf'])
        ->name('admin.statistics.monthly-pdf')
        ->middleware(['auth:admin']);

    // Add these Excel generation routes
    Route::get('/admin/statistics/daily-excel', [App\Http\Controllers\SubscriptionController::class, 'generateDailyStatsExcel'])
        ->name('admin.statistics.daily-excel')
        ->middleware(['auth:admin']);

    Route::get('/admin/statistics/monthly-excel', [App\Http\Controllers\SubscriptionController::class, 'generateMonthlyStatsExcel'])
        ->name('admin.statistics.monthly-excel')
        ->middleware(['auth:admin']);
});

   //Settings Route:
   Route::view('/admin/settings', 'admin.admin_settings')->name('admin.settings');

   Route::post('/admin/2f-auth-state', [SettingsController::class, 'toggle2FactorAuthState'])->name('admin.toggle-2f-auth-state');
   Route::get('/admin/2f-auth-state', [SettingsController::class, 'get2FactorAuthState'])->name('admin.get-2f-auth-state');

   Route::get('/admin/pass-configurations', [SettingsController::class, 'getPasswordConfigurations'])->name('admin.get-pass-configurations');



// Admin public routes for login/register
    Route::view('/admin', 'auth.login-admin')->name('viewadmin.login');
    Route::view('/admin-register', 'auth.register-admin')->name('admin.register');
    Route::post('/admin-register', [AUTHadminController::class, 'register_admin']);
    Route::view('/admin-login', 'auth.login-admin')->name('loginadmin.login');
    Route::post('/admin-login', [AUTHadminController::class, 'login_admin'])->name('admin.login');


// Redirect to admin login if not authenticated
Route::middleware(['auth:admin/login'])->group(function () {
    Route::get('/admin/{any}', function () {
        return redirect()->route('admin.login');
    })->where('any', '.*');
});


