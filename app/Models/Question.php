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

    public static function baseSearch($query_string) {
        $query = Question::query()
        ->select('q.*', 'rank')
        ->distinct();

        # TODO: add tags to weight

        $query->fromRaw(
            "content c inner join question q on c.id = q.content_id 
            inner join \"user\" u on c.author_id = u.id
            inner join question_tag qt on qt.question_id = q.content_id
            inner join tag t on t.id = qt.tag_id,
            ts_rank_cd(setweight(to_tsvector('simple', q.title), 'A') || ' ' || 
                setweight(to_tsvector('simple', c.main), 'B') || ' ' || 
                setweight(to_tsvector('simple', u.name), 'D'), 
                plainto_tsquery('simple', ?)) as rank",
            [ $query_string ]
        );
        $query->whereRaw("rank > 0");
        $query->order("rank");

        return $query;
    }

    public static function search($query_string, $rpp, $page) {
        $query = self::baseSearch($query_string)
            ->get()
            ->toArray();

        # TODO: paginate

        return $query;
    }

    public static function searchBest($query_string, $rpp, $page) {
        $query = self::baseSearch($query_string);

        $query->order('numerical');

        # TODO: paginate

        return $query->get()->toArray();
    }

    public static function searchNew($query_string, $rpp, $page) {
        $query = self::baseSearch($query_string);

        $query->order('date');

        # TODO: paginate

        return $query->get()->toArray();
    }

    public static function searchTrending($query_string, $rpp, $page) {
        $query = self::baseSearch($query_string);

        # TODO: change base query

        $query->order('date');

        # TODO: paginate

        return $query->get()->toArray();
    }

    /**
     * Used to order questions in search results
     * 
     * @param order:
     *  - 'alpha' for ascending alphanumeric titles
     *  - 'numerical' for decreasing vote difference
     *  - 'similar' for most similar to query string
     *  - otherwise ordered by latest date
     * @return none
     */
    public function scopeOrder($query, $order) {
        if ($order != null) {
            if (strcmp($order, "alpha") == 0)
                $query->orderBy('q.title', 'asc');
            else if (strcmp($order, "numerical") == 0)
                $query->orderBy('q.votes_difference', 'desc');
            else if (strcmp($order, "similar") == 0)
                $query->orderBy('rank', 'desc');
        } else {
            $query->latest('c.creation_date');
        }
    }

    /**
     * Used to filter questions by authors in search results
     * 
     * @param authorTypes list containing possible author types
     *  - 'expert' for questions made by expert users
     *  - 'own' for the authenticated user's questions
     * @return none
     */
    public function scopeAuthor($query, $author_types) {
        if ($author_types != null) {

            $query = $query->where(function ($q) use ($author_types) {
                foreach ($author_types as $author_type) {
                    if (strcmp($author_type, "expert") == 0)
                        $q->orWhere('u.expert', true);
                    else if (strcmp($author_type, "own") == 0 && Auth::check())
                        $q->orWhere('u.id', Auth::user()->id);
                }
            });
        }
    }

    /**
     * Used to filter questions by tags in search results
     * 
     * @param serialized_tags list containing serialized tag ids, separated by commas
     * @return none
     */
    public function scopeTags($query, $serialized_tags) {
        if ($serialized_tags != null) {
            $tags = explode(",", $serialized_tags);
            $query = $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhere('t.id', $tag);
                }
            });
        }
    }

    /**
     * Used to filer by the user's saved questions
     * 
     * @param 
     */
    public function scopeSaved($query) {

    }

    public function bestAnswer($id)
    {
        $array = DB::select("SELECT a.content_id FROM answer a
        WHERE a.question_id = $id AND a.is_best_answer = TRUE");

        if (count($array) > 0) {
            return $array[0]->content_id;
        }

        return null;
    }
}
