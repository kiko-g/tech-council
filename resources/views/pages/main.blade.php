@extends('layouts.app', 
  [
    'user' => $user,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
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
  @include('partials.filters.question', ['filter_prefix' => "question_search"])
  @include('partials.search.question', [
    'questions' => $questions,
    'count' => $question_count,
    'page' => 1,
    'rpp' => 6
])@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
