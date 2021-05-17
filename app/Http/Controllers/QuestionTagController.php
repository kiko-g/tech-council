<?php

namespace App\Http\Controllers;

use App\Models\QuestionTag;
use App\Models\Tag;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class QuestionTagController extends Controller
{
    /**
     * Add tag to question
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON The followed tag content in JSON format.
     */
    public function add(Request $request)
    {
        $tag_id = $request->tag_id;
        $question_id = $request->question_id;

        $this->authorize('add', Question::find($question_id));

        $question_tag = new QuestionTag();
        $question_tag->tag_id = $tag_id;
        $question_tag->question_id = $question_id;

        try {
            $question_tag->save();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        return response()->json(['tag_id' => $tag_id, 'question_id' => $question_id]);
    }

    /**
     * Remove tag from question
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON The unfollowed tag content in JSON format.
     */
    public function remove(Request $request)
    {
        $tag_id = $request->tag_id;
        $question_id = $request->question_id;

        try {
            $question_tag = QuestionTag::findOrFail(['tag_id' => $tag_id, 'question_id' => $question_id]);
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        $this->authorize('remove', $question_tag);

        try {
            $question_tag->delete();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        return response()->json(['tag_id' => $tag_id, 'question_id' => $question_id]);
    }
}
