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
	<script src="https://\{\{cdn\}\}/prism@v1.x/components/prism-core.min.js"></script>
	<script src="https://\{\{cdn\}\}/prism@v1.x/plugins/autoloader/prism-autoloader.min.js"></script>
  @include('partials.mural')
@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
