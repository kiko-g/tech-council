@extends('layouts.entry')

@section('content')
  <form method="POST" action="{{ route('password.email') }}">
    @csrf

    <header class="text-start text-light mb-4 ms-4">
      <h3>Recover password</h3>
    </header>
    <div class="form-floating mb-4">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email address</label>
      @if ($errors->has('floatingInput'))
        <span class="error">
          {{ $errors->first('floatingInput') }}
        </span>
      @endif
    </div>

      <div class="d-flex justify-content-between">
        <a href="{{ route('register') }}" class="link-light entry-anchor text-start">Don't have an account? <br> Sign up</a>
        <div>
          <input type="submit" value="Send reset link" class="btn blue" />
        </div>
      </div>
  </form>

  @if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
@endsection