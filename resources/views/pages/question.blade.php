@extends('layouts.app')

@section('content')
  @include('partials.question.card', ['question' => $question, 'include_comments' => true, 'voteValue' => $question->getVoteValue()])
  <main>
    @include('partials.question.answer-submit', ['question_id' => $question->content_id])
    @foreach ($question->answers as $answer)
      @include('partials.question.answer', ['answer' => $answer ?? '', 'voteValue' => $answer->getVoteValue()])
    @endforeach
  </main>
@endsection

@section('aside')
  @include('partials.aside')
@endsection
