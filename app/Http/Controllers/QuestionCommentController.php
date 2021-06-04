<?php

namespace App\Http\Controllers;

use App\Models\QuestionComment;
use App\Models\Question;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class QuestionCommentController extends Controller
{
    /**
     * Create a comment to a question.
     *
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        Question::findOrFail($request->question_id);

        $request->validate(['main' => 'required|max:' . QuestionComment::MAX_MAIN_LENGTH]);

        $content = new Content();
        $content->main = $request->main;
        $content->author_id = Auth::user()->id;

        $question_comment = null;
        try {
            DB::transaction(function () use ($content, $request, &$question_comment) {
                $content->save();
                $question_comment = new QuestionComment();
                $question_comment->content_id = $content->id;
                $question_comment->question_id = $request->question_id;
                $question_comment->save();
            });
        } catch (PDOException $e) {
            abort(403, $e->getMessage());
        }

        return response()->json(['parent_id' => $request->question_id, 'comment' => view('partials.question.comment', ['comment' => $question_comment])->render()]);
    }
}
