<?php

// Route::group(['middleware' => 'web'], function () {
//     Route::get('/{any}', 'LaravueController@index')->where('any', '.*');

// });

use App\Http\Controllers\Auth\SocialLogin;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/dash641', 'HomeController@admin');
Route::get('/dash641/{any}', 'HomeController@admin')->where('any', '.*');

Route::get('/auth/login/{provider}' , 'Auth\SocialLogin@redirectToProvider') ;

Route::any('/auth/login/{provider}/callback' , 'Auth\SocialLogin@handleProviderCallback')->name('socialCallback');


Route::get('/sitemap-{type}.xml', 'XmlMap@xml') ;


//Testing
Route::get('/dashboard', function(){
   return view('frontend.dashboard.index');
});

Route::get('/ask-teacher', function(){
    return view('frontend.ask-teacher');
 });

//Route::get('/home', 'HomeController@index')->name('home');
