<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContentReport extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_report';
    protected $primaryKey = 'content_id';
    const MAX_MAIN_LENGTH = 1000;


    public function report()
    {
        return $this->belongsTo('App\Models\Report', 'report_id');
    }

    public function content()
    {
        return $this->belongsTo('App\Models\Content', 'content_id');
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
    
    public function get_question_id($type, $content_id) {
        error_log($type);
        switch ($type) {
            case 'Question':
                Question::findOrFail($content_id);
                return $content_id;

            case 'Answer':
                $answer = Answer::findOrFail($content_id);
                return $answer->question->content_id;

            case 'QuestionComment':
                $comment = QuestionComment::findOrFail($content_id);
                return $comment->question->content_id;
            
            case 'AnswerComment':
                $comment = AnswerComment::findOrFail($content_id);
                return $comment->question->content_id;
                
            default:
                break;
        }
    }
}
