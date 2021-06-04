@extends('layouts.profile', 
  [
    'user' => $user,
    'user_questions' => $user_questions,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
      'app.js',
      'vote.js',
      'report.js',
      'follow.js',
      'save.js',
      'profile-filters.js'
    ]
  ]
)

@section('content')
  @include('partials.filters.profile')
  <section id="user-questions">
    @include('partials.search.question', [
      'questions' => $user_questions,
      'count' => $question_count,
      'page' => 1,
      'rpp' => 6
    ])
  </section>
@endsection

@section('aside')
  @include('partials.user.aside', ['user' => $user])
@endsection
