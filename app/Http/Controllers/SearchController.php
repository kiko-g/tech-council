<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        /*
        $request->validate([
            'bundled' => ['required', 'boolean'],
			'questions.query_string' => ['max:' . SearchController::MAX_QUERY_STRING_LENGTH],
			'questions.rpp' => ['required', 'integer'],
			'questions.page' => ['required', 'integer'],
			'questions.type' =>[ function ($attribute, $value, $fail) {
				if($value != '' && $value != 'best' && $value != 'new' && $value != 'trending') {
                    $fail('The '.$attribute.' must be "best", "new" or "trending"');
				}
            },
            'tags.query_string'
        ],
		]);
        */
        
        $questions = $this->searchQuestions($request);
        $tags = $this->searchTags($request);
        $users = $this->searchUsers($request);

        error_log("QUESTIONS");
        error_log(json_encode($questions));
        error_log("TAGS");
        error_log(json_encode($tags));
        error_log("USERS");
        error_log(json_encode($users));

        $results = json_encode(array('questions' => $questions, 'tags' => $tags, 'users' => $users));
    }

    /**
	 * Search for questions (full text search)
	 * 
	 * @param Request $request An HTTP request containing the query fields
	 * @return |Illuminate\Http\Response
	 */
    public function searchQuestions(Request $request) {
        $params = (object) array();

        $request->validate([
            'bundled' => ['required', 'boolean']
        ]);

        if($request->bundled) {
            $request->validate([
                'questions.query_string' => ['max:' . SearchController::MAX_QUERY_STRING_LENGTH],
                'questions.author' => ['string' . SearchController::MAX_QUERY_STRING_LENGTH],
                'questions.tags' => ['string' . SearchController::MAX_QUERY_STRING_LENGTH],
                'questions.rpp' => ['required', 'integer'],
                'questions.page' => ['required', 'integer'],
                'questions.type' =>[ function ($attribute, $value, $fail) {
                    if($value != '' && $value != 'best' && $value != 'new' && $value != 'trending') {
                        $fail('The '.$attribute.' must be "best", "new" or "trending"');
                    }
                }],
            ]);

            $params = $request->questions;
        } else {
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

            $params->$request;
        }

		if(is_null($params->query_string)) {
			$params->query_string = "";
		}
		if(is_null($params->author)) {
			$params->author = "";
		}
        if(is_null($params->tags)) {
			$params->tags = "";
		}

        $tags = explode(";", $params->tags);

		$results = DB::select(
            "SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited, u.name, rank
            FROM content c inner join question q on c.id = q.content_id inner join 'user' u on c.author_id = u.id,
            ts_rank_cd(setweight(to_tsvector('simple', q.title), 'A') || ' ' || setweight(to_tsvector('simple', c.main), 'B') || ' ' || setweight(to_tsvector('simple', u.name), 'D'), plainto_tsquery('simple', :query)) as rank
            WHERE rank > 0
            ORDER BY rank DESC
            OFFSET :offset
			LIMIT :limit",
			[
				'query' => $params->query_string,
				'offset' => $params->rpp*($params->page - 1),
				'limit' => $params->rpp
			]
		);

		return json_encode($results);

    }

    /**
	 * Search for a tag (full text search)
	 * 
	 * @param Request $request An HTTP request containing the query fields
	 * @return |Illuminate\Http\Response
	 */
    public function searchTags(Request $request) {
        $params = (object) array();

        $request->validate([
            'bundled' => ['required', 'boolean']
        ]);

        if($request->bundled) {
            $request->validate([
                'tags.query_string' => ['max:' . SearchController::MAX_QUERY_STRING_LENGTH],
                'tags.rpp' => ['required', 'integer'],
                'tags.page' => ['required', 'integer'],
                'tags.type' =>[ function ($attribute, $value, $fail) {
                    if($value != '' && $value != 'best' && $value != 'new' && $value != 'trending') {
                        $fail('The '.$attribute.' must be "best", "new" or "trending"');
                    }
                }],
            ]);
            
            $params = $request->tags;
        } else {
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

            $params = $request;
        }

		if(is_null($params->query_string)) {
			$params->query_string = "";
		}

		$results = DB::select(
			"SELECT t.name, t.description, ts_rank_cd(to_tsvector(t.name), plainto_tsquery('simple', :query)) as rank
			FROM tag t
			WHERE t.name @@ plainto_tsquery('english', :query)
			ORDER BY rank DESC
			OFFSET :offset
			LIMIT :limit",
			[
				'query' => $params->query_string,
				'offset' => $params->rpp*($params->page - 1),
				'limit' => $params->rpp
			]
		);

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
