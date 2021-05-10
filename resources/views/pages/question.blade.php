@extends('layouts.app')

@section('content')
  @include('partials.question.card', ['question' => $question, 'include_comments' => true, 'voteValue' => $question->getVoteValue()])
  @include('partials.question.answer-submit', ['question_id' => $question->content_id])
  <section id="answers">
    @foreach ($question->answers as $answer)
      @include('partials.question.answer', ['answer' => $answer ?? '', 'voteValue' => $answer->getVoteValue()])
    @endforeach
  </section>
@endsection

@section('aside')
  @include('partials.aside')
@endsection
