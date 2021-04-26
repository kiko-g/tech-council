@extends('layouts.app')

@section('content')
  @include('partials.tag-card')
  @include('partials.question-card')

  <?php buildTag(null); ?>

  @include('partials.division')
  @include('partials.filters')

  <?php
  buildQuestion(null);
  buildQuestion(null);
  ?>
@endsection

@section('aside')
  @include('partials.aside')
@endsection