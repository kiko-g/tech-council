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

Route::get('sendmail','MailController@send');

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
Route::get('/user/{id}', 'UserController@showProfile')->name('user');                       // R201
Route::get('/user/{id}/settings', 'UserController@showProfileSettings');                    // R202
Route::put('/user/{id}/edit', 'UserController@saveProfileSettings');                        // R203


/* 
----------------------------------
M03: Content viewing and searching
TODO: R303, R305
----------------------------------
*/
Route::get('/', 'MainController@showMural')->name('home');                                  // R301
/*
Route::get('search', function () {
    return view('pages.search', [
        'questions' => Question::paginate(10),
        'tags' => Tag::paginate(10),
        'users' => User::paginate(7),
        'user' => Auth::user(),
    ]);
});                                                                                         // R302
*/
Route::get('search', 'SearchController@search')->name('search');
Route::get('/api/search/tag', 'SearchController@searchTags');      
Route::get('/api/search/question', 'SearchController@searchQuestions');                     // R304
Route::get('/question/{id}', 'QuestionController@showPage')->name('question');              // R306
Route::get('/answer/{id}', 'QuestionController@showPage')->name('answer');
Route::get('tag/{id}', 'TagController@showPage')->name('tag');                              // R307


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
Route::post('/api/create/question', 'QuestionController@create');

Route::get('/edit/question/{id}', 'QuestionController@edit');
Route::put('/api/edit/question/{id}', 'QuestionController@update');

Route::post('/api/follow/tag', 'FollowTagController@follow');
Route::post('/api/unfollow/tag', 'FollowTagController@unfollow'); //TODO: delete
Route::post('/api/save/question', 'SaveQuestionController@save');
Route::delete('/api/unsave/question', 'SaveQuestionController@unsave'); //TODO: delete

Route::put('/api/tag/{id}/edit', 'TagController@edit');
Route::delete('/api/tag/{id}/delete', 'TagController@delete');
Route::delete('/api/question/remove_tag', 'QuestionTagController@remove');

Route::post('/api/question/insert', 'QuestionController@create')->name('question.create');          // create question 
Route::delete('/question/{id}/delete', 'QuestionController@delete');        // delete question
Route::delete('/api/question/{id}/delete', 'QuestionController@deleteAPI'); // delete question ajax

Route::post('/api/question/{id}/answer', 'AnswerController@create')->name('answer.create'); // create answer
Route::put('/api/answer/{id}/edit', 'AnswerController@edit');               // edit answer
Route::delete('/api/answer/{id}/delete', 'AnswerController@delete');        // delete answer
Route::get('/api/answer/{id}', 'AnswerController@get');
Route::post('/api/answer/{id}/best', 'AnswerController@setBest');

Route::post('/api/question/{id}/vote', 'QuestionController@addVote');       // insert question vote
Route::put('/api/question/{id}/vote', 'QuestionController@addVote');        // edit question vote
Route::delete('/api/question/{id}/vote', 'QuestionController@deleteVote');  // delete question vote
Route::post('/api/answer/{id}/vote', 'AnswerController@addVote');           // insert answer vote
Route::put('/api/answer/{id}/vote', 'AnswerController@addVote');            // edit answer vote
Route::delete('/api/answer/{id}/vote', 'AnswerController@deleteVote');      // delete answer vote

Route::post('/api/content/{id}/report', 'ReportController@reportContent');
Route::post('/api/user/{id}/report', 'ReportController@reportUser');

Route::post('/api/answer/comment/insert', 'AnswerCommentController@insert');
Route::post('/api/question/comment/insert', 'QuestionCommentController@insert');

/* TODO:
--------------- 
M05: Moderation
---------------
*/
Route::get('moderator', 'ModeratorController@showArea')->middleware('api');

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
