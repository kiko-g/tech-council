@extends('layouts.app', 
  [
    'user' => $user,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
      'comment.js',
      'app.js',
      'vote.js',
      'report.js',
      'best-answer.js',
      'follow.js',
      'save.js',
    ]
  ]
)

@section('content')
  @include('partials.question.card', ['question' => $question, 'include_comments' => true, 'voteValue' => $question->getVoteValue()])
  @include('partials.question.answer-submit', ['question_id' => $question->content_id])
  <section id="answers">
    @foreach ($question->answers as $answer)
      @include('partials.question.answer', ['answer' => $answer ?? '', 'voteValue' => $answer->getVoteValue(), 'bestAnswer' => $question->bestAnswer($question->content_id)])
    @endforeach
  </section>
@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
