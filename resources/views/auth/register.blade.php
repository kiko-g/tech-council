@extends('layouts.entry')

@section('content')
  <form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <header class="text-start text-light mb-4 ms-4">
      <h3>Sign up</h3>
    </header>
    <div class="form-floating mb-4">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating mb-4">
      <input name="username" type="text" class="form-control" id="floatingInput" placeholder="username" required>
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating mb-4">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
    </div>
    <div class="form-floating mb-4">
      <input name="password_confirmation" type="password" class="form-control" id="floatingPasswordConf"
        placeholder="Password" required>
      <label for="floatingPasswordConf">Confirm password</label>
    </div>
    <div class="d-flex justify-content-between">
      <a href="{{ route('login') }}" class="link-light entry-anchor text-start">Already have an account? <br> Sign
        in</a>
      <input type="submit" value="Submit" class="btn blue-alt" />
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
