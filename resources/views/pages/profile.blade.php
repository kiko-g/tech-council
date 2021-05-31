@extends('layouts.profile', ['user' => $user, 'user_questions' => $user_questions])

@section('content')
  @include('partials.filters.profile')
  <section id="user-questions">
    @foreach ($user_questions as $question)
      @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
    @endforeach
  </section>
  @include('partials.pagination')
@endsection

@section('aside')
  @include('partials.user.aside', ['user' => $user])
@endsection
