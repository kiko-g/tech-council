<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
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

    public function followTags() {
        return $this->hasMany('App\Models\FollowTag');
    }
}
