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
}
