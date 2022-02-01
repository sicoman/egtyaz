<?php

Route::post('register', 'Frontend\Api\UserController@register');
Route::post('login', 'Frontend\Api\UserController@login');
Route::post('social/login-or-register', 'Frontend\Api\UserController@loginOrRegisterBySocial');
Route::post('contact' , 'Frontend\Api\HomeController@Postcontact')->name('postContact') ;

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'Frontend\Api\UserController@getAuthenticatedUser');

    Route::get('/packages', 'Frontend\Api\PackageController@index')->name('packages');
    Route::get('/packages/{id}/buy', 'Frontend\Api\PackageController@beforeBuy')->name('buy_package_details');
    Route::get('/packages/{id}/buy/{processor}', 'Frontend\Api\PackageController@buy')->name('buy_package');
    Route::get('/packages/{id}/buy/{processor}/purchased', 'Frontend\Api\PackageController@bought')->name('package_purchased');

    Route::get('coupon/{code}', 'Frontend\Api\DashboardController@previewCoupon')->name('previewCoupon_api');


    
Route::group(['prefix' => 'cp','middleware' => 'App\Http\Middleware\PackageAccess:bank' ], function () {
    Route::group(['prefix' => 'bank'], function () {
        Route::get('/', 'Frontend\Api\DashboardController@index')->name('bank');
        Route::get('/{category_id}/subjects', 'Frontend\Api\DashboardController@subjects')->name('category_subjects');
        Route::get('/{category_id}/subjects/{subject_id}', 'Frontend\Api\DashboardController@skills')->name('subject_skills');
        Route::get('/{category_id}/subjects/{subject_id}/{skill_id}', 'Frontend\Api\DashboardController@skillQuestions')->name('skill_questions');
        
        Route::get('challenges', 'Frontend\Api\ChallengesController@index')->name('challenges') ;
        Route::get('challenges/old', 'Frontend\Api\ChallengesController@old')->name('old_challenges') ;
        Route::post('challenges/create', 'Frontend\Api\ChallengesController@createChallenge')->name('createChallenge') ;
        Route::get('my-exams', 'Frontend\Api\ExamController@index')->name('my-exams') ;
        Route::get('my-exam/{id}', 'Frontend\Api\ExamController@myExam')->name('my-exam') ;
        //createChallenge
    });
    
    Route::get('user/search', 'Frontend\Api\ProfileController@searchUser')->name('searchUsers');
    Route::post('user/update-avatar', 'Frontend\Api\ProfileController@updateAvatar')->name('updateAvatar');
    Route::post('/profile', 'Frontend\Api\ProfileController@index')->name('profile');
    Route::get('/profile', 'Frontend\Api\ProfileController@index')->name('profile');
    Route::get('/logout', 'Auth\LoginController@logoutUser')->name('logout');

    
    Route::get('exam', 'Frontend\Api\DashboardController@freeExam')->name('exam') ;
    Route::post('exam', 'Frontend\Api\DashboardController@makeExam')->name('Makeexam') ;
    Route::get('exam/results/{exam}', 'Frontend\Api\DashboardController@ExamResult')->name('ExamResult') ;
    Route::get('exam/mine', 'Frontend\Api\DashboardController@index')->name('myExams') ;
    Route::get('exam/{exam}', 'Frontend\Api\DashboardController@startExam')->name('startExam') ;
    Route::post('exam/{exam}', 'Frontend\Api\DashboardController@saveExam')->name('saveExam') ;

    
    

    
    Route::get('wishlist', 'Frontend\Api\ProfileController@wishlist')->name('wishlist') ;
    Route::post('wishlist/add-or-remove', 'Frontend\Api\ProfileController@addOrRemoveInWishlist')->name('addOrRemoveInWishlist');


    Route::get('askTeacher', 'Frontend\Api\AskTeacherController@ask')->name('askTeacher') ;
    Route::post('askTeacher', 'Frontend\Api\AskTeacherController@goAsk')->name('goAskTeacher') ;

    Route::get('askTeacher/list', 'Frontend\Api\AskTeacherController@index')->name('askTeacherList') ;
    Route::get('askTeacher/my', 'Frontend\Api\AskTeacherController@my')->name('askTeacherMine') ;
    Route::get('askTeacher/{ask}', 'Frontend\Api\AskTeacherController@show')->name('askTeacherShow') ;
    Route::post('askTeacher/{ask}', 'Frontend\Api\AskTeacherController@answer')->name('answerAsk')   ;
});

});