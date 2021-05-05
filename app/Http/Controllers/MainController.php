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
		$questions = Question::paginate(10);
		return view('pages.main', [
			'questions' => $questions,
			'user' => Auth::user()
		]);
	}
}
