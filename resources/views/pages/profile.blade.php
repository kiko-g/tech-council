@extends('layouts.profile', ['user' => $user, 'user_questions' => $user_questions])

@section('content')
  @include('partials.filters.profile')
  <section>
    @foreach ($user_questions as $question)
      @include('partials.question.card', ['question' => $question, 'include_comments' => false])
    @endforeach
  </section>
  @include('partials.pagination')
@endsection

@section('aside')
  @include('partials.user.aside')
@endsection
