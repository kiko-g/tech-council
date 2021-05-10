<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Content;
use App\Models\Question;
use App\Models\VoteAnswer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $question_id)
    {
        Question::findOrFail($question_id);

        $this->authorize('create', Answer::class);
        $request->validate(['main' => 'required|max:' . Answer::MAX_MAIN_LENGTH]);

        $content = new Content();
        $content->main = $request->input('main');
        $content->author_id = Auth::user()->id;

        try {
            DB::transaction(function () use ($content, $question_id) {
                $content->save();
                $answer = new Answer();
                $answer->content_id = $content->id;
                $answer->question_id = $question_id;
                $answer->save();
            });
        } catch (PDOException $e) {
            error_log($e->getMessage());
            abort(403, $e->getMessage());
        }

        // TODO: Add notification here!

        return response()->json($content);
    }

    /**
     * Get the answer, with the id specified by the parameter, view (HTML code).
     *
     * @param  \App\Models\Answer  $answer
     * @return the specified answer view.
     */
    public function get($id)
    {
        $answer = Answer::find($id);
        return view('partials.question.answer', ['answer' => $answer ?? '', 'voteValue' => $answer->getVoteValue()]);
    }

    /**
     * Edit an answer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id - the answer id.
     * @return JSON The answer content in JSON format.
     */
    public function edit(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('edit', $answer);
        $content = $answer->content;

        //TODO: trigger to update edited date
        $content->main = $request->main;
        try {
            $content->save();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        return response()->json($content);
    }

    /**
     * Delete an answer.
     *
     * @return JSON The answer id in JSON format.
     */
    public function delete($id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('delete', $answer);
        $content = $answer->content;

        try {
            $content->delete();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        $content = [
            'id' => $id
        ];
        return response()->json($content);
    }

    public function addVote(Request $request, $content_id)
    {
        Answer::findOrFail($content_id);

        $this->authorize('create', VoteAnswer::class);
        $request->validate(['value' => 'required|integer']);

        $existingVote = VoteAnswer::where('user_id', Auth::user()->id)
            ->where('answer_id',  $content_id)
            ->first();

        if ($existingVote != null) {
            $existingVote->vote = $request->value;
            try {
                $existingVote->save();
            } catch (PDOException $e) {
                abort('403', $e->getMessage());
            }
        } else {
            $vote = new VoteAnswer();
            $vote->user_id = Auth::user()->id;
            $vote->answer_id = $content_id;
            $vote->vote = $request->value;
            try {
                $vote->save();
            } catch (PDOException $e) {
                abort('403', $e->getMessage());
            }
        }
    }

    public function deleteVote($content_id)
    {
        Answer::findOrFail($content_id);

        $this->authorize('delete', VoteAnswer::class);

        VoteAnswer::where('user_id', Auth::user()->id)
            ->where('answer_id', $content_id)
            ->delete();
    }
}
