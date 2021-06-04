@extends('layouts.profile', 
  [
    'user' => $user,
    'user_questions' => $user_questions,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
      'comment.js',
      'app.js',
      'vote.js',
      'report.js',
      'follow.js',
      'save.js',
      'filters.js'
    ]
  ]
)

@section('content')
  @include('partials.filters.profile')
  <section id="user-questions">
    @foreach ($user_questions as $question)
      @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
    @endforeach
  </section>
@endsection

@section('aside')
  @include('partials.user.aside', ['user' => $user])
@endsection
