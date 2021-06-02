<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'question';
    protected $primaryKey = 'content_id';

    const MAX_TITLE_LENGTH = 100;
    const MAX_MAIN_LENGTH = 1000;

    public function content()
    {
        return $this->belongsTo('App\Models\Content', 'content_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'question_tag', 'question_id', 'tag_id');
    }

    public function answers()
    {
        return $this->hasMany('App\Models\Answer', 'question_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\QuestionComment', 'question_id');
    }

    public function getVoteValue()
    {
        if (!Auth::check())
            return 0;

        $voteQuestion = VoteQuestion::where('user_id', Auth::user()->id)
            ->where('question_id', $this->content_id)
            ->first();

        return is_null($voteQuestion) ? 0 : $voteQuestion->vote;
    }

    public function isReportedByUser()
    {
        //TODO:
        if (!Auth::check())
            return false;
            
        error_log("ola");

        $content_report = DB::table('content_report')
            ->join('report', 'content_report.report_id', '=', 'report.id')
            ->where('content_id', $this->content_id)
            ->where('reporter_id', Auth::user()->id)
            ->get();
        
        return count($content_report) > 0 ? true : false;
    }

    public function countInteractions() {
        $counter = count($this->answers) + count($this->comments);
        foreach($this->answers as $answer) {
            $counter += count($answer->comments);
        }

        return $counter;
    }

    public static function search($query_string, $rpp, $page) {
        return DB::select(
            "SELECT q.content_id, q.title, q.votes_difference, c.main, c.creation_date, c.edited, u.name, rank
            FROM content c inner join question q on c.id = q.content_id inner join \"user\" u on c.author_id = u.id,
            ts_rank_cd(setweight(to_tsvector('simple', q.title), 'A') || ' ' || setweight(to_tsvector('simple', c.main), 'B') || ' ' || setweight(to_tsvector('simple', u.name), 'D'), plainto_tsquery('simple', :query)) as rank
            WHERE rank > 0
            ORDER BY rank DESC
            OFFSET :offset
			LIMIT :limit",
			[
				'query' => $query_string,
				'offset' => $rpp*($page - 1),
				'limit' => $rpp
			]
		);
    }
}
