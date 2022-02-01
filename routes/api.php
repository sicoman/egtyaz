<?php

use Illuminate\Http\Request;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) { return $request->user();});

/**
 * Testing social login
 */

Route::get('payment/create/{bookingId}' , 'Payments\PaypalController@auth') ;
Route::any('payment/{bookingId}/cancel' , 'Payments\PaypalController@cancel') ;
Route::any('payment/{bookingId}/auth/success' , 'Payments\PaypalController@successAuth') ;
#Route::any('payment/{bookingId}/success' , 'Payments\PaypalController@success') ;

Route::any('payment/{bookingId}/ipn' , 'Payments\PaypalController@ipn') ;



Route::get('payment/payfort/create/{bookingId}' , 'Payments\PayfortController@auth') ;
Route::any('payment/payfort/handel' , 'Payments\PayfortController@success') ;
Route::any('payment/payfort/capture/{bookingId}' , 'Payments\PayfortController@payNow') ;

Route::group(['middleware' => 'api'], function () {


    Route::post('auth/login', 'AuthController@login');
    Route::post('auth/forget', 'Auth\ForgotPasswordController@forgetPassword')->name('forgot.password');
    Route::post('auth/reset', 'Auth\ForgotPasswordController@resetPassword')->name('password.reset');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/logout', 'AuthController@logout');
    });

    Route::any('/auth/token/{provider}', 'Auth\SocialLogin@tokenLogin');

    Route::any('/auth/login/{provider}/callback' , 'Auth\SocialLogin@handleProviderCallback')->name('socialCallback');

    Route::any('users/select', 'UserController@select_list')->name('users.select');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::apiResource('users', 'UserController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::post('users/active/{taxonomy}', 'UserController@active')->name('user.active');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::post('users/verifiy/{taxonomy}', 'UserController@verifiy')->name('user.verifiy');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::post('users/area/{user}', 'UserController@area')->name('user.area');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('users/{user}/permissions', 'UserController@permissions');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::put('users/{user}/permissions', 'UserController@updatePermissions');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('roles', 'RoleController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::get('roles/{role}/permissions', 'RoleController@permissions');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('permissions', 'PermissionController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    
    Route::apiResource('options', 'OptionsController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);

    Route::apiResource('exams', 'ExamsController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::post('exams/active/{exam}', 'ExamsController@active')->name('Exam.active');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::any('exams/select/{type}', 'ExamsController@select')->name('Exam.select');


    Route::apiResource('payments', 'PaymentsController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::post('payments/active/{booking}', 'PaymentsController@active')->name('Payments.active');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);

    #Route::apiResource('reviews', 'ReviewsController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    #Route::post('reviews/active/{review}', 'ReviewsController@active')->name('reviews.active');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);

    Route::apiResource('flags', 'FlagsController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::post('flags/active/{flag}', 'FlagsController@active')->name('flags.active');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);

    Route::apiResource('orders', 'FlagsController');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);

    Route::get('dashboard', 'DashboardController@index');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('stat', 'DashboardController@stat');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);

    Route::apiResource('log', 'LogController'); // ->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);



    Route::apiResource('badges', 'BadgeController');
    Route::post('badges/active/{badge}', 'BadgeController@active')->name('badges.active');


});

Route::group([ 'prefix' => '/admin' ], function () { // 'middleware' => 'api:auth' ,
    /*
     * Deal With Upload Files
     */
    Route::post('upload', 'fileController@handle')->name('fileUpload');
    /*
     * Deal With Taxonomies And Taxonomies Types
     */
    Route::any('taxonomy/select', 'Admin\TaxonomyController@select_list')->name('taxonomy.select');//->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('taxonomy', 'Admin\TaxonomyController@index')->name('taxonomy.index');
    Route::post('taxonomy', 'Admin\TaxonomyController@store')->name('taxonomy.store');
    Route::delete('taxonomy/{taxonomy}', 'Admin\TaxonomyController@destroy')->name('taxonomy.destroy');
    Route::post('taxonomy/active/{taxonomy}', 'Admin\TaxonomyController@active')->name('taxonomy.active');
    Route::get('taxonomy/{type}', 'Admin\TaxonomyController@parents')->name('taxonomy.parents') ;

    /*
        Deal With Packages
    */
    Route::get('packages', 'Edu\PackagesController@index')->name('package.index');
    Route::post('packages', 'Edu\PackagesController@store')->name('package.store');
    Route::delete('packages/{taxonomy}', 'Edu\PackagesController@destroy')->name('package.destroy');
    Route::post('packages/active/{taxonomy}', 'Edu\PackagesController@active')->name('package.active');
    Route::any('packages/select/', 'Edu\PackagesController@select')->name('package.select');

    /*
        Deal With Copouns
    */
    Route::get('copouns', 'Edu\CopounsController@index')->name('copouns.index');
    Route::post('copouns', 'Edu\CopounsController@store')->name('copouns.store');
    Route::delete('copouns/{taxonomy}', 'Edu\CopounsController@destroy')->name('copouns.destroy');
    Route::post('copouns/active/{taxonomy}', 'Edu\CopounsController@active')->name('copouns.active');

    /*
        Deal With Questions
    */
    Route::any('questions/bulk/{action}', 'Edu\QuestionsController@bulk')->name('questions.bulk');
    Route::get('questions', 'Edu\QuestionsController@index')->name('questions.index');
    Route::post('questions', 'Edu\QuestionsController@store')->name('questions.store');
    Route::delete('questions/{taxonomy}', 'Edu\QuestionsController@destroy')->name('questions.destroy');
    Route::post('questions/active/{taxonomy}', 'Edu\QuestionsController@active')->name('questions.active');
    Route::any('questions/select/', 'Edu\QuestionsController@select')->name('questions.select');

    /*
     * Deal With Posts And Posts Types
     */
    Route::get('post', 'Admin\PostController@index')->name('posts.index');
    Route::post('post', 'Admin\PostController@store')->name('posts.store');
    Route::delete('post/{taxonomy}', 'Admin\PostController@destroy')->name('posts.destroy');
    Route::post('post/active/{taxonomy}', 'Admin\PostController@active')->name('posts.active');
    Route::get('post/{type}', 'Admin\PostController@parents')->name('posts.parents') ;
    Route::any('post/select/{type}', 'Admin\PostController@select')->name('posts.select');

    /*
     * Deal With Courses And
     */
    Route::get('course', 'CoursesController@index')->name('courses.index');
    Route::post('course', 'CoursesController@store')->name('courses.store');
    Route::delete('course/{taxonomy}', 'CoursesController@destroy')->name('courses.destroy');
    Route::post('course/active/{taxonomy}', 'CoursesController@active')->name('courses.active');
    Route::any('course/select', 'CoursesController@select')->name('courses.select');

    Route::get('users/list', 'UserController@users')->name('users.list') ;


    /*
     * Deal With Contact Types
     */
    Route::get('contact', 'Admin\ContactController@index')->name('contact.index');
    Route::post('contact', 'Admin\ContactController@store')->name('contact.store');
    Route::delete('contact/{taxonomy}', 'Admin\ContactController@destroy')->name('contact.destroy');
    Route::post('contact/active/{taxonomy}', 'Admin\ContactController@active')->name('contact.active');
    Route::get('contact/{type}', 'Admin\ContactController@parents')->name('contact.parents') ;



    Route::get('tickets', 'Admin\ZendeskController@index')->name('tickets.index');
    Route::get('tickets/{id}', 'Admin\ZendeskController@show')->name('tickets.show');
    Route::post('tickets/{id}', 'Admin\ZendeskController@update')->name('tickets.update');
    Route::delete('tickets/{taxonomy}', 'Admin\ZendeskController@destroy')->name('tickets.destroy');
    Route::post('tickets/active/{taxonomy}', 'Admin\ZendeskController@active')->name('tickets.active');


    /*
     * Deal With Comments
     */
    Route::get('comment', 'Admin\CommentController@index')->name('comments.index');
    Route::post('comment', 'Admin\CommentController@store')->name('comments.store');
    Route::delete('comment/{taxonomy}', 'Admin\CommentController@destroy')->name('comments.destroy');
    Route::post('comment/active/{taxonomy}', 'Admin\CommentController@active')->name('comments.active');
    Route::get('comment/{type}', 'Admin\CommentController@parents')->name('comments.parents') ;

    Route::get('users/list', 'UserController@users')->name('users.list') ;




});

Route::get('taxonomy/{type}', 'Admin\TaxonomyController@parents')->name('taxonomy.parents') ;


Route::group(['prefix' => '/cron' ], function () {
    Route::get('currency', 'CronJobs@currency')->name('CronJobs.currency');
    Route::get('setPrice', 'CronJobs@setPrice')->name('CronJobs.setPrice');
    Route::get('setBooking', 'CronJobs@setBooking')->name('CronJobs.setBooking');
    Route::get('setBookingHour', 'CronJobs@setBookingHour')->name('CronJobs.setBookingHour');
    Route::get('lastSearch', 'CronJobs@lastSearch')->name('CronJobs.lastSearch');
});

Route::post('static/send', 'Api\StaticPages@contact')->name('StaticPage.SendMessage');

Route::post('static/apply_job', 'Api\StaticPages@apply_job')->name('StaticPage.apply_job');

Route::get('static/content/{post}', 'Api\StaticPages@post')->name('StaticPage.Post');

Route::get('static/search', 'Api\StaticPages@search')->name('StaticPage.search');

Route::get('static/{page}', 'Api\StaticPages@handle')->name('StaticPage.Handeler');

Route::post('subscribe', 'Api\SettingsController@subscribe')->name('maillist.subscribe');

Route::get('settings/{type}', 'Api\SettingsController@handle')->name('Settings.Handeler');


Route::group([ 'prefix' => '/front' ], function () {
    Route::get('units/taxonomy', 'front\UnitsC@category');
    Route::resource('units', 'front\UnitsC')->middleware('jwt.verify');
    Route::post('upload', 'front\fileController@handle')->name('fileUpload');
    Route::get('unhandeled', 'front\fileController@handleUploaded')->name('handleUploaded');
    // Test Function
    Route::get('filterCity', 'front\UnitsC@filterCity') ;

    Route::get('new/units/{id}', 'front\UnitsC@showN')->middleware('jwt.verify');
    Route::put('new/units/{id}', 'front\UnitsC@updateN')->middleware('jwt.verify');
    
});

Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('front/mobile/confirm', 'front\FrontController@confirm') ;
    Route::get('front/mobile/validate/{code}', 'front\FrontController@code_validate') ;
    Route::get('front/notifications', 'front\FrontController@Notifications') ;
});

Route::get('token/{provider}', 'auth\SocialLogin@tokenLogin');

//Rest api 
Route::group([ 'prefix' => '/v1' ], function () {  //echo Hash::make("gaber"); die;
    Route::resource('posts', 'v1\PostsController') ;
    Route::resource('taxonomy', 'v1\TaxonomyController') ;
    Route::resource('users', 'v1\AuthController') ;
    Route::post('users/authViaSocial', 'v1\AuthController@authViaSocialLogin') ;
    Route::post('users/login', 'v1\AuthController@login') ;
    Route::post('users/forget', 'v1\AuthController@forget') ;
    Route::post('users/resend', 'v1\AuthController@resend') ;
    Route::post('users/verify', 'v1\AuthController@verify') ;
    Route::post('users/update-id-photo', 'v1\AuthController@updateIdPhoto') ;
    Route::get('users', 'v1\UsersController@index') ;
    Route::get('whoami', 'v1\UsersController@currentUser') ;
    Route::get('my-notifications', 'v1\UsersController@getNotifications') ;
    Route::get('my-wishlist', 'v1\WishListController@index') ;
    Route::get('unit-days', 'v1\UnitDaysController@index') ;
    Route::get('unit-bookings', 'v1\UnitsController@bookings') ;
    Route::get('is-booking-available', 'v1\UnitsController@IsDaysAvilable') ; //addToWishList
    Route::post('add-unit-to-wishlist', 'v1\UnitsController@addToWishList') ; 
    Route::post('update-unit-days', 'v1\UnitsController@updateOrAddDays') ; 
    Route::post('flag-unit', 'v1\UnitsController@flagUnit') ; 
    Route::post('add-review', 'v1\UnitsController@addReview') ; 
    Route::post('set-booking-status', 'v1\UnitsController@setBookingStatus') ;
    Route::post('add-booking', 'v1\UnitsController@addBooking') ; 

    /*
        Units Payouts
    */
    Route::get('payouts', 'v1\UnitsController@Payouts') ;
    Route::get('payments', 'v1\UnitsController@Payments') ;
    Route::get('lunits', 'v1\UnitsController@Lunits'); //->middleware('jwt.verify') ; 

    
    Route::group(['middleware' => 'jwt.verify'], function () {
        Route::get('listpayouts', 'v1\UnitsController@ListClientPayouts') ;
        Route::get('listpayments', 'v1\UnitsController@ListClientPayments') ;
        Route::get('listunits', 'v1\UnitsController@ListOwnerunits');
    });


});

Route::get('xml', 'XmlMap@index') ;


Route::apiResource('challengers', 'ChallengerController');



/*
use App\Models\Booking ;
use App\Models\BookingCancel ;

$booking    = Booking::findOrFail(2);

$cancel     = BookingCancel::Calculate($booking , '2019-11-03') ;

BookingCancel::create(['unit_id' => $booking->unit_id,'price' => $cancel->price, 'cancel_id' => $booking->unit->cancel_policy]);

Days::whereIn($daysavailable)->where('unit_id', $booking->unit_id)->update(['status' => 1]);

$booking->update(['status' => -3]);

dd($cancel) ;


 
    $booking->updated_at = date('Y-m-d h:i:s') ;
    $booking->status = 1 ;
    $booking->save();

use App\Models\Units ;
$Units = Units::findorFail(1);
$Units->updated_at = date('Y-m-d h:i:s') ;
$Units->status = 0 ;
$Units->save();
*/

/*
use App\Models\Options ;
$list = [
    'user' => [
        'user_welcome' ,
        'user_forget_password',
        'user_active_mobile'
    ],
    'unit' => [
        'unit_incomplete' ,
        'unit_confirm' ,
        'unit_missing_photos' ,
        'unit_changes_confirm'
    ],
    'booking' => [
        'booking_request' ,
        'booking_confirm' ,
        'booking_paid' ,
        'booking_checkin' ,
        'booking_checkout' ,
        'booking_expired' ,
        'booking_canceled' ,
        'booking_cancel_accept' ,
        'booking_reject'
    ],
    'reviews' => [
        'review_trip' ,
        'review_add' ,
        'review_submitted'
    ]
];

foreach( $list['reviews'] as $user ){
    Options::create([
        'type' => 'notifications_email',
        'option_var' => $user ,
        'option_value' => '' ,
        'html' => 'editor' ,
        'description' => str_replace('_' , ' ' , $user) ,
        'status' => 1
    ]);
    Options::create([
        'type' => 'notifications_email_ar',
        'option_var' => $user ,
        'option_value' => '' ,
        'html' => 'editor' ,
        'description' => str_replace('_' , ' ' , $user) ,
        'status' => 1
    ]);
    Options::create([
        'type' => 'notifications_sms',
        'option_var' => $user ,
        'option_value' => '' ,
        'html' => 'textarea' ,
        'description' => str_replace('_' , ' ' , $user) ,
        'status' => 1
    ]);
    Options::create([
        'type' => 'notifications_sms_ar',
        'option_var' => $user ,
        'option_value' => '' ,
        'html' => 'textarea' ,
        'description' => str_replace('_' , ' ' , $user) ,
        'status' => 1
    ]);

    Options::create([
        'type' => 'notifications_via',
        'option_var' => $user ,
        'option_value' => '[]' ,
        'html' => 'multi_select' ,
        'description' => str_replace('_' , ' ' , $user) ,
        'status' => 1
    ]);
}
die();
*/
