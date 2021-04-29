<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Content;
use App\Models\VoteQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\AssignOp\Concat;

class QuestionController extends Controller
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
    public function create(Request $request)
    {
        $this->authorize('create', Question::class);

        // TODO: Add request validation

        $content = new Content();
        $question = new Question($content->id);

        $content->main = $request->input('main');
        $content->author_id = Auth::user()->id;
        $question->title = $request->input('title');

        DB::transaction(function () use ($content, $question) {
            $content->save();
            $question->save();
        });

        return $question;
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
     * $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::find($id);
        $this->authorize('show', $question);
        return view('partials.question.card', [
            'question' => $question,
            'tags' => $question->tags,
            'content' => $question->content,
            'answers' => $question->answers,
            'comments' => $question->comments,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * $id
     * @return \Illuminate\Http\Response
     */
    public function showPage($id)
    {
        $question = Question::find($id);
        //echo $question->votes_difference;
        return view('pages.question', [
            'question' => $question,
            'user' => Auth::user()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function show2($id)
    {
        $question = Question::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }

    public function addVote(Request $request)
    {
        echo $request;
    }

    public function insertQuestion(Request $request)
    {
        echo $request;
    }
}
