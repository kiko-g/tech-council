<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /**
     * No 'create' and 'update' timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'bio'
    ];

    public function profile_photo_obj() {
        return $this->belongsTo('App\Models\Photo', 'profile_photo');
    }

    public function moderator() {
        return $this->hasOne('App\Models\Moderator', 'user_id');
    }

    public function questions() {
        return $this->hasManyThrough('App\Models\Question', 'App\Models\Content', 'author_id', 'content_id', 'id', 'id');
    }

    public function answers() {
        return $this->hasManyThrough('App\Models\Answer', 'App\Models\Content', 'author_id', 'content_id', 'id', 'id');
    }

    public function questionComments() {
        return $this->hasManyThrough('App\Models\QuestionComment', 'App\Models\Content', 'author_id', 'content_id', 'id', 'id');
    }

    public function answerComments() {
        return $this->hasManyThrought('App\Models\AnswerComment', 'App\Models\Content', 'author_id', 'content_id', 'id', 'id');
    }

    public function followedTags() {
        return $this->hasMany('App\Models\FollowTag');
    }

    public function followsTag($tag_id) {
        $user_id = Auth::user()->id;

        $follow_tag = FollowTag::where([
            'user_id' => $user_id,
            'tag_id' => $tag_id,
        ])->first();

        if (!empty($follow_tag)) {
            return true;
        }
        return false;
    }

    public function hasQuestionSaved($question_id, $user_id)
    {
        $save_question = SaveQuestion::where([
            'user_id' => $user_id,
            'question_id' => $question_id,
        ])->first();

        if (!empty($save_question)) {
            return true;
        }
        return false;
    }    

    public static function search($query_string, $rpp, $page)
    {
        return DB::select(
            "SELECT u.\"name\", u.email, u.bio, u.join_date, u.expert, u.banned, p.path as photo_path
            FROM \"user\" u INNER JOIN photo p ON u.profile_photo = p.id, 
            ts_rank_cd(to_tsvector(u.name), plainto_tsquery('simple', :query)) as rank
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

    public function isReportedByUser()
    {
        if (!Auth::check())
            return false;

        $user_report = DB::table('user_report')
        ->join('report', 'user_report.report_id', '=', 'report.id')
        ->where('user_id', $this->id)
            ->where('reporter_id', Auth::user()->id)
            ->get();

        return count($user_report) > 0 ? true : false;
    }
}
