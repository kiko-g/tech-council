<?php

namespace App\Http\Controllers;

use App\Models\AnswerComment;
use App\Models\Answer;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class AnswerCommentController extends Controller
{
    const MAX_MAIN_LENGTH = 1000;

    /**
     * Create comment to an answer.
     *
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        Answer::findOrFail($request->answer_id);

        $request->validate(['main' => 'required|max:' . AnswerComment::MAX_MAIN_LENGTH]);

        $content = new Content();
        $content->main = $request->main;
        $content->author_id = Auth::user()->id;

        $answer_comment = null;
        try {
            DB::transaction(function () use ($content, $request, &$answer_comment) {
                $content->save();
                $answer_comment = new AnswerComment();
                $answer_comment->content_id = $content->id;
                $answer_comment->answer_id = $request->answer_id;
                $answer_comment->save();
            });
        } catch (PDOException $e) {
            abort(403, $e->getMessage());
        }

        return response()->json(['parent_id' => $request->answer_id, 'comment' => view('partials.question.comment', ['comment' => $answer_comment])->render()]);
    }
}
