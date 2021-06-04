@extends('layouts.entry')

@section('content')
  <form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <header class="text-start text-light mb-4 ms-4">
      <h3>Sign in</h3>
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
    <div class="form-floating mb-4">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
      @if ($errors->has('floatingPassword'))
        <span class="error">
          {{ $errors->first('floatingPassword') }}
        </span>
      @endif
    </div>
    <div class="d-flex justify-content-between">
      <a href="{{ route('register') }}" class="link-light entry-anchor text-start">Don't have an account? <br> Sign up</a>
      <div>
        <button onclick="" class="btn wine">Request password change</button>
        <input type="submit" value="Submit" class="btn blue" />
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
