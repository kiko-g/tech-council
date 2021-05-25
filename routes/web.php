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

/* M01: Authentication */
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('/login/reset', 'Auth\RecoverController@show');

/* M02: Individual Profile */
Route::get('/user/{id}', 'UserController@showProfile');

/* M03: Content viewing and searching */
Route::get('/', 'MainController@showMural')->name('home');
Route::get('/question/{id}', 'QuestionController@showPage');
Route::get('/api/search/tag', 'TagController@search');

/* M04: Content interaction */
Route::get('/create/question', function () {
    return view('pages.ask', [
        'user' => Auth::user(),
    ]);
})->name('create/question');

Route::post('/api/question/insert', 'QuestionController@create');           // create question 
Route::delete('/api/question/{id}/delete', 'QuestionController@deleteAPI'); // delete question ajax
Route::delete('/question/{id}/delete', 'QuestionController@delete');        // delete question

Route::post('/api/question/{id}/answer', 'AnswerController@create')->name('answer.create'); // create answer
Route::put('api/answer/{id}/edit', 'AnswerController@edit');                // edit answer
Route::put('api/answer/{id}/delete', 'AnswerController@delete');            // delete answer

Route::post('/api/question/{id}/vote', 'QuestionController@addVote');       // insert question vote
Route::put('/api/question/{id}/vote', 'QuestionController@addVote');        // edit question vote
Route::delete('/api/question/{id}/vote', 'QuestionController@deleteVote');  // delete question vote
Route::post('/api/answer/{id}/vote', 'AnswerController@addVote');           // insert answer vote
Route::put('/api/answer/{id}/vote', 'AnswerController@addVote');            // edit answer vote
Route::delete('/api/answer/{id}/vote', 'AnswerController@deleteVote');      // delete answer vote


/* M05: Moderation */
// --

/* M06: Static Pages */
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
