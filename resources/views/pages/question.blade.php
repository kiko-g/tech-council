{{-- @extends('layouts.app') --}}

{{-- @section('content')
  @include('partials.card', ['card' => $card])
@endsection --}}

@include('partials.head')

<body>
	@include('partials.header')
	<main class="container">
		<div class="row">
			@include('partials.question-mural')
			@include('partials.aside')
		</div>
	</main>
	@include('partials.footer')
</body>

</html>