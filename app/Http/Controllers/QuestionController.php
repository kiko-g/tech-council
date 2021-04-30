<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Content;
use App\Models\VoteQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

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

        $request->validate([
            'title' => ['required', 'max:' . Question::MAX_TITLE_LENGTH],
            'main' => ['required', 'max:' . Question::MAX_TITLE_LENGTH],
            'tags' => ['required', function ($attribute, $value, $fail) {
                $tags = explode(' ', $value);
                if(count($tags) !== count(array_flip($tags))) {
                    $fail('The '.$attribute.' must have unique tags.');
                }
                if(count($tags) < 1 || count($tags) > 1) {
                    $fail('The '.$attribute.' must have between 1 and 5 tags.');
                }
            }] // Parse and check if there are between 1 and 5 tags -> no repeated
        ]);

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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $question = Question::find($id);

		$this->authorize('delete', $question);
		$question->delete();

        return redirect()->route('home');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAPI($id)
    {
        $question = Question::find($id);

		$this->authorize('delete', $question);

        try {
            $question->delete();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        $content = [
            'id' => $id
        ];
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

    public function addVote(Request $request, $content_id)
    {
        Question::findOrFail($content_id);

        $this->authorize('create', VoteQuestion::class);
        $request->validate(['value' => 'required|integer']);

        $existingVote = VoteQuestion::where('user_id', Auth::user()->id)
            ->where('question_id',  $content_id)
            ->first();
        
        if($existingVote != null) {
            $existingVote->vote = $request->value;
            try {
                $existingVote->save();
            } catch (PDOException $e) {
                abort('403', $e->getMessage());
            } 
        } else {
            $vote = new VoteQuestion();
            $vote->user_id = Auth::user()->id;
            $vote->question_id = $content_id;
            $vote->vote = $request->value;
            try {
                $vote->save();
            } catch (PDOException $e) {
                abort('403', $e->getMessage());
            }
        }
    }

    public function deleteVote($content_id) {
        Question::findOrFail($content_id);

        $this->authorize('delete', VoteQuestion::class);

        VoteQuestion::where('user_id', Auth::user()->id)
            ->where('question_id', $content_id)
            ->delete();
    }
}
