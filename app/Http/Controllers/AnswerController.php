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
            DB::transaction(function() use($content, $question_id) {
                $content->save();
                $answer = new Answer();
                $answer->content_id = $content->id;
                $answer->question_id = $question_id;
                $answer->save();
            });
        } 
        catch (PDOException $e) {
            error_log($e->getMessage());
            abort(403, $e->getMessage());
        }
 
        // TODO: Add notification here!

        return response()->json($content);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }

    public function addVote(Request $request, $content_id)
    {
        Answer::findOrFail($content_id);

        $this->authorize('create', VoteAnswer::class);
        $request->validate(['value' => 'required|integer']);

        $existingVote = VoteAnswer::where('user_id', Auth::user()->id)
            ->where('answer_id',  $content_id)
            ->first();
        
        if($existingVote != null) {
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

    public function deleteVote($content_id) {
        Answer::findOrFail($content_id);

        $this->authorize('delete', VoteAnswer::class);

        VoteAnswer::where('user_id', Auth::user()->id)
            ->where('answer_id', $content_id)
            ->delete();
    }
}
