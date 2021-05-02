@extends('layouts.entry')

@section('content')
  <form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <header class="text-start text-light mb-4 ms-4">
      <h3>Password recovery</h3>
    </header>
    <div class="form-floating mb-4">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating mb-4">
      <button class="btn blue-alt">
        Send confirmation email
      </button>
    </div>
    <div class="form-floating mb-4">
      <input name="password" type="password" class="form-control" id="floatingConfirmation" placeholder="Confirmation code" required>
      <label for="floatingConfirmation">Code</label>
    </div>
    <div class="form-floating mb-4">
      <button class="btn blue-alt">
        Confirm
      </button>
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
