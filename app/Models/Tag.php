<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag';

    public function questions() {
        return $this->belongsToMany('App\Models\Question', 'question_tag', 'tag_id', 'question_id');
    }

    public function followers() {
        return $this->belongsToMany('App\Models\User', 'follow_tag', 'tag_id', '"user_id"');
    }
}
