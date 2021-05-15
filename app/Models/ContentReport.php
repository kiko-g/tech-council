<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContentReport extends Model
{
    /**
     * No 'create' and 'update' timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $table = 'content_report';

    public function content() {
        return $this->belongsTo('App\Models\Content', 'content_id');
    }

    public function report() {
        return $this->belongsTo('App\Models\Report', 'report_id');
    }

    public function content_type_reported($content_id) {
        try {
            Question::findOrFail($content_id);
            return "Question";
        } catch(ModelNotFoundException $e) {
            try {
                Answer::findOrFail($content_id);
                return "Answer";
            } catch(ModelNotFoundException $e) {
                return "Comment";
            }
        }
    }
    
    public function get_question_id($content_id) {
        try {
            Question::findOrFail($content_id);
            return $content_id;
        } catch(ModelNotFoundException $e) {
            try {
                $answer = Answer::findOrFail($content_id);
                return $answer->question->id;
            } catch(ModelNotFoundException $e) {
                //TODO: when comment model is implemented
            }
        }
    }
}
