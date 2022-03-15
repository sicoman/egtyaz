<?php

use Illuminate\Support\Facades\Route;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Facades\PayPal;

Route::group(['middleware' => ['web']], function () {

    Auth::routes();

    Route::get('/', 'Frontend\HomeController@index')->name('home');

    Route::get('sharer/exam/results/{exam}', 'Frontend\DashboardController@ExamResult')->name('ExamResult');

    //Social login
    Route::get('auth/{provider}', 'Auth\SocialLogin@redirectToProvider');
    Route::get('auth/{provider}/callback', 'Auth\SocialLogin@handleProviderCallback');

    // HomePage Basic Routes
    Route::get('page/{name}', 'Frontend\HomeController@page')->name('page');
    Route::get('contact', 'Frontend\HomeController@contact')->name('contact');
    Route::post('contact', 'Frontend\HomeController@Postcontact')->name('postContact');


    Route::any('paypal/complete', function () {
        dd($_GET);
    });
    Route::get('paypal', function () {

        $provider = PayPal::setProvider('express_checkout');

        $data = [];
        $data['items'] = [
            [
                'name' => "Name of product",
                'price' => 200,
                'desc' => ' New Booking',
                'qty' => 1
            ]
        ];

        $data['invoice_id'] = uniqid() . '-' . time();
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = url('/paypal/complete');
        $data['cancel_url'] = url('/api/payment/cancel');

        $data['total'] = 200;


        $options = [
            'BRANDNAME' => 'Edu',
            'LOGOIMG' => 'https://momaiz.net/images/logo.png',
            'CHANNELTYPE' => 'Merchant'
        ];

        $provider->addOptions($options);

        $response = $provider->setExpressCheckout($data);

        dd($response);


    });

    Route::get('exam/{code}', 'Frontend\DashboardController@examResultShare')->name('exam.share');
});

Route::group(['middleware' => ['web', 'auth:web'], 'prefix' => 'cp'], function () {

    Route::group(['prefix' => 'bank', 'middleware' => 'App\Http\Middleware\PackageAccess:bank'], function () {
        Route::get('/', 'Frontend\DashboardController@index')->name('bank');
        Route::get('/{category_id}/subjects', 'Frontend\DashboardController@subjects')->name('category_subjects');
        Route::get('/{category_id}/subjects/{subject_id}', 'Frontend\DashboardController@skills')->name('subject_skills');
        Route::get('/{category_id}/subjects/{subject_id}/{skill_id}', 'Frontend\DashboardController@skillQuestions')->name('skill_questions');
        //createChallenge
    });

    Route::group(['prefix' => 'bank', 'middleware' => 'App\Http\Middleware\PackageAccess:challenges'], function () {
        Route::get('challenges', 'Frontend\ChallengesController@index')->name('challenges');
        Route::get('challenges/old', 'Frontend\ChallengesController@old')->name('old_challenges');
        Route::get('challenges/collective', 'Frontend\ChallengesController@collective')->name('collective');
        Route::get('challenges/collective/{id?}', 'Frontend\ChallengesController@collectiveExams')->name('collectiveExams');
        Route::post('challenges/create', 'Frontend\ChallengesController@createChallenge')->name('createChallenge');
        Route::get('my-exams', 'Frontend\DashboardController@Exams')->name('my-exams');
        Route::get('my-exam/{id}', 'Frontend\DashboardController@ExamResult')->name('my-exam');
        //createChallenge
    });

    Route::get('user/search', 'Frontend\ProfileController@searchUser')->name('searchUsers');
    Route::post('user/update-avatar', 'Frontend\ProfileController@updateAvatar')->name('updateAvatar');
    Route::post('/profile', 'Frontend\ProfileController@index')->name('profile');
    Route::get('/profile', 'Frontend\ProfileController@index')->name('profile');
    Route::get('/rewards', 'Frontend\RewardsController@index')->name('rewards');
    Route::post('/rewards', 'Frontend\RewardsController@index')->name('buy');
    Route::get('/logout', 'Auth\LoginController@logoutUser')->name('logout');

    Route::get('/packages', 'Frontend\PackageController@index')->name('packages');
    Route::get('/packages/{id}/buy', 'Frontend\PackageController@beforeBuy')->name('buy_package_details');
    Route::get('/packages/{id}/buy/{processor}', 'Frontend\PackageController@buy')->name('buy_package');
    Route::get('/packages/{id}/buy/{processor}/purchased', 'Frontend\PackageController@bought')->name('package_purchased');

    Route::group(['middleware' => 'App\Http\Middleware\PackageAccess:exam'], function () {
        Route::get('exams/free', 'Frontend\DashboardController@Exams')->name('exams');
        Route::get('exams/mock', 'Frontend\DashboardController@Mocks')->name('mocks');
        Route::get('exams/subjects/{id}', 'Frontend\DashboardController@examsSubjects')->name('exSubjects');
        Route::get('exam', 'Frontend\DashboardController@freeExam')->name('exam');
        Route::post('exam', 'Frontend\DashboardController@makeExam')->name('Makeexam');
        Route::get('exam/results/{exam}', 'Frontend\DashboardController@ExamResult')->name('ExamResult');
        Route::get('exam/mine', 'Frontend\DashboardController@myExams')->name('myExams');
        Route::get('exam/{exam}', 'Frontend\DashboardController@startExam')->name('startExam');
        Route::post('exam/{exam}', 'Frontend\DashboardController@saveExam')->name('saveExam');
    });

    Route::group(['middleware' => 'App\Http\Middleware\PackageAccess:ask_teacher'], function () {
        Route::get('askTeacher', 'Frontend\AskTeacherController@ask')->name('askTeacher');
        Route::post('askTeacher', 'Frontend\AskTeacherController@goAsk')->name('goAskTeacher');

        Route::get('askTeacher/list', 'Frontend\AskTeacherController@index')->name('askTeacherList');
        Route::get('askTeacher/my', 'Frontend\AskTeacherController@my')->name('askTeacherMine');
        Route::get('askTeacher/{ask}', 'Frontend\AskTeacherController@show')->name('askTeacherShow');
        Route::post('askTeacher/{ask}', 'Frontend\AskTeacherController@answer')->name('answerAsk');
    });

    Route::group(['middleware' => 'App\Http\Middleware\PackageAccess:comunity'], function () {
        //Community
        Route::get('community/new-post', 'Frontend\CommunityController@newPost')->name('community_new_post');
        Route::post('community', 'Frontend\CommunityController@addPost')->name('add_community_post');
        Route::get('community', 'Frontend\CommunityController@index')->name('community');
        Route::get('community/{category}', 'Frontend\CommunityController@category')->name('community_category');
        Route::get('community/{category}/{post}', 'Frontend\CommunityController@post')->name('community_post');
        Route::post('add_community_comment/{post}', 'Frontend\CommunityController@comment')->name('add_community_comment');
    });

    Route::group(['middleware' => 'App\Http\Middleware\PackageAccess:exam'], function () {

    });

    Route::group(['middleware' => 'App\Http\Middleware\PackageAccess:exam'], function () {

    });

    Route::group(['middleware' => 'App\Http\Middleware\PackageAccess:exam'], function () {


    });


    Route::get('wishlist', 'Frontend\ProfileController@wishlist')->name('wishlist');
    Route::post('wishlist/add-or-remove', 'Frontend\ProfileController@addOrRemoveInWishlist')->name('addOrRemoveInWishlist');
    Route::post('wishlist/bulk-delete', 'Frontend\ProfileController@deleteQuestionsFromWishlist')->name('deleteQuestionsFromWishlist');


    //Notifications
    Route::get('notifications', 'Frontend\NotificationController@index')->name('notifications');


    Route::get('question/{id}', 'Frontend\DashboardController@Question')->name('question');

    //   Route::get('discount/{id}', 'Frontend\DashboardController@previewCoupon')->name('previewCoupon') ;
    Route::get('coupon/{code}', 'Frontend\DashboardController@previewCoupon')->name('previewCoupon');

    Route::get('rate', 'Frontend\DashboardController@rate')->name('rate');

    Route::get('/', 'Frontend\DashboardController@cpanel')->name('cpanel');

    Route::group(['prefix' => 'start', 'middleware' => 'App\Http\Middleware\PackageAccess:foundation'], function () {
        Route::get('/', 'Frontend\DashboardController@start')->name('start');
        Route::get('/{category_id}/subjects', 'Frontend\DashboardController@startSubjects')->name('start_category_subjects');
        Route::get('/{category_id}/subjects/{subject_id}', 'Frontend\DashboardController@startSkills')->name('start_skills');
        Route::get('/{category_id}/subjects/{subject_id}/{skill_id}', 'Frontend\DashboardController@StartskillQuestions')->name('skill_questions');
    });

    //Route::get('elearning', 'Frontend\ElearningController@index')->name('elearning');
    Route::get('elearning/show/{id}', 'Frontend\ElearningController@show')->name('elearning.show');
    Route::get('elearning/{parent}', 'Frontend\ElearningController@index')->name('elearning');
    Route::get('elearning/{parent}/{category?}', 'Frontend\ElearningController@index')->name('elearning.category');
    Route::get('ajax/elearning', 'Frontend\ElearningController@singleAjax')->name('singleAjax');


    Route::group(['prefix' => 'courses', 'middleware' => 'App\Http\Middleware\PackageAccess:courses'], function () {
        Route::get('/join/{id}/{title?}', 'Frontend\DashboardController@joinCourse')->name('Joincourse');
        Route::get('/{id}/{title?}', 'Frontend\DashboardController@getCourse')->name('course');
        Route::get('/{cat?}', 'Frontend\DashboardController@courses')->name('courses');
    });

    Route::any('bankm', function () {

    });
//
});

Route::group(['prefix' => 'bm', 'middleware' => ['web']], function () {
    Route::get('/form/{payment}', 'Frontend\BankMisrController@form')->name('bm.form');
    Route::get('/success', 'Frontend\BankMisrController@success')->name('bm.success');
    Route::get('/cancel', 'Frontend\BankMisrController@cancel')->name('bm.cancel');
    Route::get('/callback', 'Frontend\BankMisrController@callback')->name('bm.callback');
});

Route::group(['prefix' => 'pp', 'middleware' => ['web']], function () {
    Route::get('/form/{payment}', 'Frontend\PaypalController@form')->name('pp.form');
    Route::get('/success', 'Frontend\PaypalController@success')->name('pp.success');
    Route::get('/cancel', 'Frontend\PaypalController@cancel')->name('pp.cancel');
});

Route::group(['prefix' => 'pt', 'middleware' => ['web']], function () {
    Route::any('/form/{payment}', 'Frontend\PaytabsController@form')->name('pt.form');
    Route::any('/success', 'Frontend\PaytabsController@success')->name('pt.success');
    Route::any('/cancel', 'Frontend\PaytabsController@cancel')->name('pt.cancel');
});

Route::any('/ipn', 'Frontend\PaypalController@ipn')->name('pp.ipn');
Route::any('/bm_callback', 'Frontend\BankMisrController@callback')->name('bm.callback');
Route::any('/bt_callback', 'Frontend\PaytabsController@callback')->name('pt.callback');
