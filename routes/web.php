<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Home
Route::get('/', function() {
    return view('pages.main');
});

// Cards
Route::get('cards', 'CardController@list');
Route::get('cards/{id}', 'CardController@show');

// API
Route::put('api/cards', 'CardController@create');
Route::delete('api/cards/{card_id}', 'CardController@delete');
Route::put('api/cards/{card_id}/', 'ItemController@create');
Route::post('api/item/{id}', 'ItemController@update');
Route::delete('api/item/{id}', 'ItemController@delete');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Static pages
Route::get('about', function() {
    return view('pages.about');
});

Route::get('faq', function() {
    return view('pages.faq');
});

Route::get('ask', function() {
    return view('pages.ask');
});

Route::get('moderator', function() {
    return view('pages.moderator');
});

Route::get('profile', function() {
    return view('pages.profile');
});

Route::get('profile-settings', function() {
    return view('pages.profile-settings');
});

Route::get('question', function() {
    return view('pages.question');
});

Route::get('tag', function() {
    return view('pages.tag');
});

Route::get('search', function() {
    return view('pages.search');
});

// Error page
//Route::get('no_match', 'Pages\404')