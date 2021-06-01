<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function isBest()
    {
        if (!Auth::check()) 
            return 0;

        return $this->question();
    }
}
