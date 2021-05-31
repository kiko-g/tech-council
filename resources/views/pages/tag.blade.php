@extends('layouts.app', ['user' => $user])

@section('content')

  @include('partials.tag.card', ['tag' => $tag, 'user' => $user])

  @include('partials.division')
  @include('partials.filters.question')

  @foreach ($tag->questions as $question)
    @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
  @endforeach
@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
