<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
}
