<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\SaveQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class SaveQuestionController extends Controller
{
    /**
     * Save a question
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON The saved question content in JSON format.
     */
    public function save(Request $request)
    {
        error_log("ola 1");
        $user_id = Auth::user()->id;
        $question_id = $request->id;

        error_log("ola 1.1");
        $this->authorize('save', SaveQuestion::class);

        error_log("ola 2");
        $save_question = new SaveQuestion();
        error_log("ola 3");
        $save_question->user_id = $user_id;
        error_log("ola 4");
        $save_question->question_id = $question_id;
        
        try {
            error_log("ola 4.1");
            $save_question->save();
            error_log("ola 4.2");
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        error_log("ola 5");
        return response()->json(Question::find($question_id));
    }

    /**
     * Unsave a question
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON The unsaved question content in JSON format.
     */
    public function unsave(Request $request)
    {
        $user_id = Auth::user()->id;
        $question_id = $request->id;

        $save_question = SaveQuestion::findOrFail(['user_id' => $user_id, 'question_id' => $question_id]);

        $this->authorize('unsave', SaveQuestion::class);

        try {
            $save_question->delete();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        return response()->json(Question::find($question_id));
    }
}
