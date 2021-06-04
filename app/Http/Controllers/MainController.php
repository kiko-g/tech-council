<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Question;

class MainController extends Controller
{
	/**
	 * Display the main page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showMural()
	{
		$question_results = Question::searchBest('', 6, 1);
		return view('pages.main', [
			'questions' => Question::hydrate($question_results['data']),
			'question_count' => $question_results['count'],
			'user' => Auth::user()
		]);
	}
}
