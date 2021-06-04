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
      'tag-search.js',
      'tag-moderate.js',
    ]
  ]
)

@section('content')

  @include('partials.tag.card', ['tag' => $tag, 'user' => $user])

  @include('partials.division')
  @include('partials.filters.question', ['filter_prefix' => 'tag'])

  @foreach ($tag->questions as $question)
    @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
  @endforeach
@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
