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
use Illuminate\Support\Facades\Route;

// M01: Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// M02: Individual Profile
// --

// M03: Content viewing and searching
Route::get('/', 'MainController@showMural');
Route::get('/question/{id}', 'QuestionController@showPage');
Route::post('/api/vote/insert', 'QuestionController@addVote');
Route::delete('/api/vote/{id}/delete', 'QuestionController@deleteVote');

// M04: Content interaction
// --

// M05: Moderation
// --

// M06: Static Pages
Route::get('about', function () {
    return view('pages.about');
});

Route::get('faq', function () {
    return view('pages.faq');
});

Route::get('ask', function () {
    return view('pages.ask');
});

Route::get('moderator', function () {
    return view('pages.moderator');
});

Route::get('profile', function () {
    return view('pages.profile');
});

Route::get('profile-settings', function () {
    return view('pages.profile-settings');
}); // remove this

Route::get('question', function () {
    return view('pages.question');
});

Route::get('tag', function () {
    return view('pages.tag');
});

Route::get('search', function () {
    return view('pages.search');
});

Route::get('moderator', function () {
    return view('pages.moderator');
});

Route::get('*', function () {
    return abort(404);
});
