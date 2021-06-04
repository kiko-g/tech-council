<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionComment extends Model
{
    const MAX_MAIN_LENGTH = 1000;

    public $timestamps = false;
    protected $table = 'question_comment';
    protected $primaryKey = 'content_id';

    public function content()
    {
        return $this->belongsTo('App\Models\Content', 'content_id');
    }
}
