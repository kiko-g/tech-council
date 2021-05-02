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

use App\Http\Controllers\AnswerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Auth check
Route::get('auth/check', function () {
    return Auth::check();
});

// M01: Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// M02: Individual Profile
Route::get('/user/{id}', 'UserController@showProfile');

// M03: Content viewing and searching
Route::get('/', 'MainController@showMural')->name('home');
Route::get('/question/{id}', 'QuestionController@showPage');
Route::post('/api/question/{id}/vote', 'QuestionController@addVote');
Route::put('/api/question/{id}/vote', 'QuestionController@addVote');
Route::delete('/api/question/{id}/vote', 'QuestionController@deleteVote');
Route::post('/api/question/{id}/answer', 'AnswerController@create')->name('answer.create');
Route::post('/api/answer/{id}/vote', 'AnswerController@addVote');
Route::put('/api/answer/{id}/vote', 'AnswerController@addVote');
Route::delete('/api/answer/{id}/vote', 'AnswerController@deleteVote');

// M04: Content interaction
Route::get('/create/question', function () {
    return view('pages.ask', [
        'user' => Auth::user(),
    ]);
})->name('create/question');
Route::post('/api/question/insert', 'QuestionController@create');
Route::delete('/api/question/{id}/delete', 'QuestionController@deleteAPI');
Route::delete('/question/{id}/delete', 'QuestionController@delete');

Route::put('api/answer/{id}/edit', 'AnswerController@edit');
Route::put('api/answer/{id}/delete', 'AnswerController@delete');

// M05: Moderation
// --

// M06: Static Pages
Route::get('about', function () {
    return view('pages.about', [
        'user' => Auth::user(),
    ]);
})->name('about');

Route::get('faq', function () {
    return view('pages.faq', [
        'user' => Auth::user(),
    ]);
})->name('faq');


// JUST FOR DEBUGGING
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

Route::get('tag', function () {
    return view('pages.tag');
});

Route::get('search', function () {
    return view('pages.search');
});

Route::get('moderator', function () {
    return view('pages.moderator');
});
