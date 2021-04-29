@extends('layouts.app')

@section('content')
  @include('partials.question.card', ['question' => $question, 'include_comments' => true, 'voteValue' => $question->getVoteValue()])
  @include('partials.question.answer-submit')
  @foreach ($question->answers as $answer)
    @include('partials.question.answer', ['answer' => $answer ?? ''])
  @endforeach
@endsection

@section('aside')
  @include('partials.aside')
@endsection
