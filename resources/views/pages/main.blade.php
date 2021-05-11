@extends('layouts.app', ['user' => $user])

@section('content')
  @include('partials.mural')
@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
