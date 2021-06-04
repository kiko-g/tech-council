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

        $question_results = Question::search($request->q, 6, 1);
        $tag_results = Tag::searchFull($request->q, 6, 1);
        $user_results = User::searchFull($request->q, 6, 1);
        
        return view('pages.search', [
            'query_string' => $request->q,
            'questions' => Question::hydrate($question_results['data']),
            'question_count' => $question_results['count'],
            'tags' => Tag::hydrate($tag_results['data']),
            'tag_count' => $tag_results['count'],
            'users' => User::hydrate($user_results['data']),
            'user_count' => $user_results['count'],
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
                if($value !== '' && $value !== 'best' && $value !== 'new' && $value !== 'trending') {
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
        if(is_null($request->isView)) {
            $request->isView = 0;
        }

        $tags = explode(";", $request->tags);
        $results = [];

        switch($request->type) {
            case 'best':
                $results = Question::searchBest($request->query_string, $request->rpp, $request->page);
                break;
            case 'new':
                $results = Question::searchNew($request->query_string, $request->rpp, $request->page);
                break;
            case 'trending':
                $results = Question::searchTrending($request->query_string, $request->rpp, $request->page);
                break;
            default:
                $results = Question::search($request->query_string, $request->rpp, $request->page);
                break;
        }

        return view('partials.search.question', [
            'questions' => Question::hydrate($results['data']),
            'count' => $results['count'],
            'page' => $request->page,
            'rpp' => $request->rpp
        ]);
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
            'type' => [ function ($attribute, $value, $fail) {
                if($value != '' && $value != 'popular' && $value != 'recent') {
                    $fail('The '.$attribute.' must be "best", "new" or "trending"');
                }
            }],
            'is_view' => ['boolean'],
        ]);


		if(is_null($request->query_string)) {
			$request->query_string = "";
		}

        if(is_null($request->is_view)) {
            $request->is_view = 0;
        }

        if($request->is_view) {
            $results = Tag::searchFull($request->query_string, $request->rpp, $request->page);
            return view('partials.search.tag', [
                'tags' => Tag::hydrate($results['data']),
                'count' => $results['count'],
                'page' => $request->page,
                'rpp' => $request->rpp,
                'user' => Auth::user()
            ]);
        } else {
            $results = Tag::searchSimple($request->query_string, $request->rpp, $request->page);
            return json_encode($results);
        }
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
