<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Answer extends Model
{
    public $timestamps = false;
    protected $table = 'answer';
    protected $primaryKey = 'content_id';

    const MAX_MAIN_LENGTH = 1000;

    public function content()
    {
        return $this->belongsTo('App\Models\Content', 'content_id');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\Question', 'question_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\AnswerComment', 'answer_id');
    }

    public function getVoteValue() {
        if(!Auth::check())
            return 0;

        $voteAnswer = VoteAnswer::where('user_id', Auth::user()->id)
            ->where('answer_id', $this->content_id)
            ->first();

        return is_null($voteAnswer) ? 0 : $voteAnswer->vote;
    }

    public function isReportedByUser()
    {
        if (!Auth::check())
            return false;

        $content_report = DB::table('content_report')
        ->join('report', 'content_report.report_id', '=', 'report.id')
        ->where('content_id', $this->content_id)
            ->where('reporter_id', Auth::user()->id)
            ->get();

        return count($content_report) > 0 ? true : false;
    }

    public static function search($rpp, $page, $author) 
    {
        $query = Answer::query()
        ->select('a.*', 'c.*')
        ->distinct();

        $query->fromRaw(
            "content c inner join answer a on c.id = a.content_id 
            inner join \"user\" u on c.author_id = u.id"
        );

        $query->author($author);

        $query->latest('c.creation_date');

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

    public function scopeAuthor($query, $author) {
        if ($author != null) {
            $query->orWhere('c.author_id', $author);
        }
    }
}
