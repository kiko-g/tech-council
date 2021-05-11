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

use App\Models\Tag;
use App\Models\User;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnswerController;


/* ------------------- */
Route::get('auth/check', function () {
    return Auth::check();
});



/* M03: Content viewing and searching */
Route::get('/', 'MainController@showMural')->name('home');
Route::get('/question/{id}', 'QuestionController@showPage');
Route::get('tag/{id}', 'TagController@showPage')->name('tag');
/*
-------------------
M01: Authentication
TODO: R106, R107
-------------------
*/
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');                   // R101
Route::post('login', 'Auth\LoginController@login');                                         // R102
Route::get('logout', 'Auth\LoginController@logout')->name('logout');                        // R103
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');   // R104
Route::post('register', 'Auth\RegisterController@register');                                // R105
Route::get('/login/reset', 'Auth\RecoverController@showResetForm');                         // R106
Route::get('/login/reset', 'Auth\RecoverController@reset');                                 // R107

/*
-----------------------
M02: Individual Profile
TODO: R203 - R205
-----------------------
*/
Route::get('/user/{id}', 'UserController@showProfile');                                     // R201
Route::get('/user/{id}/settings', 'UserController@showProfileSettings');                    // R202


/* 
----------------------------------
M03: Content viewing and searching
TODO: R303 - R305
----------------------------------
*/
Route::get('/', 'MainController@showMural')->name('home');                                  // R301
Route::get('search', function () {
    return view('pages.search', [
        'questions' => Question::paginate(10),
        'tags' => Tag::paginate(10),
        'users' => User::paginate(7),
        'user' => Auth::user(),
    ]);
});                                                                                         // R302
Route::get('/question/{id}', 'QuestionController@showPage');                                // R306


/* 
------------------------ 
M04: Content interaction 
------------------------
*/
Route::get('/create/question', function () {
    return view('pages.ask', [
        'user' => Auth::user(),
    ]);
})->name('create/question'); // could be 'ask/'?

Route::post('/api/follow/tag', 'FollowTagController@follow');
Route::post('/api/unfollow/tag', 'FollowTagController@unfollow');

Route::post('/api/question/insert', 'QuestionController@create');           // create question 
Route::delete('/api/question/{id}/delete', 'QuestionController@deleteAPI'); // delete question ajax
Route::delete('/question/{id}/delete', 'QuestionController@delete');        // delete question

Route::post('/api/question/{id}/answer', 'AnswerController@create')->name('answer.create'); // create answer
Route::put('/api/answer/{id}/edit', 'AnswerController@edit');                // edit answer
Route::delete('/api/answer/{id}/delete', 'AnswerController@delete');            // delete answer
Route::get('/api/answer/{id}', 'AnswerController@get');

Route::post('/api/question/{id}/vote', 'QuestionController@addVote');       // insert question vote
Route::put('/api/question/{id}/vote', 'QuestionController@addVote');        // edit question vote
Route::delete('/api/question/{id}/vote', 'QuestionController@deleteVote');  // delete question vote
Route::post('/api/answer/{id}/vote', 'AnswerController@addVote');           // insert answer vote
Route::put('/api/answer/{id}/vote', 'AnswerController@addVote');            // edit answer vote
Route::delete('/api/answer/{id}/vote', 'AnswerController@deleteVote');      // delete answer vote


/* TODO:
--------------- 
M05: Moderation
---------------
*/
Route::get('moderator', function () {
    return view('pages.moderator', [
        'user' => Auth::user(),
    ]);
});




/* 
-----------------
M06: Static Pages
-----------------
*/
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
