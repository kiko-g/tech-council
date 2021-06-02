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
    ]
  ]
)

@section('content')
  @include('partials.mural')
@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
