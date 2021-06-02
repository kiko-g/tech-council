<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    const MAX_QUERY_STRING_LENGTH = 100;

     /**
	 * Search bundler that searches simultaneously for questions, tags and users
	 * 
	 * @param Request $request An HTTP request containing the query fields
	 * @return |Illuminate\Http\Response
	 */
    public function search(Request $request) {
        $request->validate([
            'q' => ['required', 'string']
        ]);        

        $questions = Question::hydrate(Question::search($request->q, 5, 1));
        $tags = Tag::hydrate(Tag::search($request->q, 6, 1));
        $users = User::hydrate(User::search($request->q, 6, 1));
        
        return view('pages.search', [
            'query_string' => $request->q,
            'questions' => $questions,
            'tags' => $tags,
            'users' => $users,
            'user' => Auth::user(),
        ]);
    }

    /**
	 * Search for questions (full text search)
	 * 
	 * @param Request $request An HTTP request containing the query fields
	 * @return |Illuminate\Http\Response
	 */
    public function searchQuestions(Request $request) {
        $request->validate([
            'query_string' => ['max:' . SearchController::MAX_QUERY_STRING_LENGTH],
            'author' => ['string' . SearchController::MAX_QUERY_STRING_LENGTH],
            'tags' => ['string' . SearchController::MAX_QUERY_STRING_LENGTH],
            'rpp' => ['required', 'integer'],
            'page' => ['required', 'integer'],
            'type' =>[ function ($attribute, $value, $fail) {
                if($value != '' && $value != 'best' && $value != 'new' && $value != 'trending') {
                    $fail('The '.$attribute.' must be "best", "new" or "trending"');
                }
            }],
        ]);

		if(is_null($request->query_string)) {
			$request->query_string = "";
		}
		if(is_null($request->author)) {
			$request->author = "";
		}
        if(is_null($request->tags)) {
			$request->tags = "";
		}

        $tags = explode(";", $request->tags);

		$results = Question::search($request->query_string, $request->rpp, $request->page);

		return json_encode($results);
    }

    /**
	 * Search for a tag (full text search)
	 * 
	 * @param Request $request An HTTP request containing the query fields
	 * @return |Illuminate\Http\Response
	 */
    public function searchTags(Request $request) {
        $request->validate([
            'query_string' => ['max:' . SearchController::MAX_QUERY_STRING_LENGTH],
            'rpp' => ['required', 'integer'],
            'page' => ['required', 'integer'],
            'type' =>[ function ($attribute, $value, $fail) {
                if($value != '' && $value != 'best' && $value != 'new' && $value != 'trending') {
                    $fail('The '.$attribute.' must be "best", "new" or "trending"');
                }
            }],
        ]);


		if(is_null($request->query_string)) {
			$request->query_string = "";
		}

        $results = Tag::search($request->query_string, $request->rpp, $request->page);

		return json_encode($results);
    }

    /**
	 * Search for a user (full text search)
	 * 
	 * @param Request $request An HTTP request containing the query fields
	 * @return |Illuminate\Http\Response
	 */
    public function searchUsers(Request $request) {
        return "INCOMPLETE";
    }
}
