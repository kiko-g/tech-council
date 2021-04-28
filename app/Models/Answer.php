<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answer';
    protected $primaryKey = 'content_id';

    public function content()
    {
        return $this->belongsTo('App\Models\Content', 'content_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\AnswerComment', 'answer_id');
    }
}