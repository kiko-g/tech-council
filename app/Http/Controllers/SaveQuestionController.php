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
        $user_id = Auth::user()->id;
        $question_id = $request->id;
        $this->authorize('save', SaveQuestion::class);

        $save_question = new SaveQuestion();
        $save_question->user_id = $user_id;
        $save_question->question_id = $question_id;
        
        try {
            $save_question->save();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

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
