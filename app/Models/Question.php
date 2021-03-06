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
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'content_id', 'title'
    ];

    const MAX_TITLE_LENGTH = 100;
    const MAX_MAIN_LENGTH = 1000;
    const MIN_TITLE_LENGTH = 15;
    const MIN_MAIN_LENGTH = 25;

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

    public static function baseSearch($query_string, $tag, $author, $saved) {
        $query = Question::query()
        ->select('q.*', 'rank')
        ->distinct();

        $query->fromRaw(
            "content c inner join question q on c.id = q.content_id 
            inner join \"user\" u on c.author_id = u.id
            inner join question_tag qt on qt.question_id = q.content_id
            inner join tag t on t.id = qt.tag_id
            inner join saved_question sq on sq.question_id = q.content_id,
            ts_rank_cd(setweight(to_tsvector('simple', q.title), 'A') || ' ' || 
                setweight(to_tsvector('simple', c.main), 'B') || ' ' || 
                setweight(to_tsvector('simple', u.name), 'D'), 
                plainto_tsquery('simple', ?)) as rank",
            [ $query_string ]
        );

        if($query_string !== '') {
            $query->whereRaw("rank > 0");
            $query->order("rank");
        }

        if(isset($tag)) {
            $query->tag([$tag]);
        }

        if(isset($author)) {
            $query->author($author);
        }

        if(isset($saved)) {
            $query->saved($saved);
        }

        return $query;
    }

    public static function search($query_string, $rpp, $page, $tag=null, $author=null, $saved=null) {
        $query = self::baseSearch($query_string, $tag, $author, $saved);

        return self::paginateQuery($query, $rpp, $page);
    }

    public static function searchBest($query_string, $rpp, $page, $tag=null, $author=null, $saved=null) {
        $query = self::baseSearch($query_string, $tag, $author, $saved);

        $query->order('numerical');

        return self::paginateQuery($query, $rpp, $page);
    }

    public static function searchNew($query_string, $rpp, $page, $tag=null, $author=null, $saved=null) {
        $query = self::baseSearch($query_string, $tag, $author, $saved);

        $query->order('date');

        return self::paginateQuery($query, $rpp, $page);
    }

    public static function searchTrending($query_string, $rpp, $page, $tag=null, $author=null, $saved=null) {
        $query = self::baseSearch($query_string, $tag, $author, $saved);

        $query->orWhereRaw("date_part('day', now() - c.creation_date) < 14");

        $query->order('numerical');

        return self::paginateQuery($query, $rpp, $page);
    }

    public static function paginateQuery($query, $rpp, $page) {
        return [
            'count' => count($query->get()),
            'data' => $query
                ->offset($rpp*($page-1))
                ->limit($rpp)
                ->get()
                ->toArray()
        ];
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
    public function scopeAuthor($query, $author) {
        if ($author != null) {
            $query->orWhere('c.author_id', $author);
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

    public function scopeTag($query, $tag) {
        if ($tag != null) {
            $query->orWhere('t.id', $tag);
        }
    }

    /**
     * Used to filer by the user's saved questions
     * 
     * @param 
     */
    public function scopeSaved($query, $user) {
        if ($user != null) {
            $query->orWhere('sq.user_id', $user);
        }
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
