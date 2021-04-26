{{-- @extends('layouts.app') --}}

{{-- @section('content')
  @include('partials.card', ['card' => $card])
@endsection --}}

@extends('layouts.app')

@section('content')
	@include('partials.question-mural')
@endsection

@section('aside')
	@include('partials.aside')
@endsection