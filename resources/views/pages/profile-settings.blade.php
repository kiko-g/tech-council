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
  <div class="card mb-3">
    <div class="card-header text-white bg-petrol font-source-sans-pro rounded-top"> User Settings </div>
    <div class="row g-0">
      <div class="col-lg-4">
        @php
          if (!isset($user->profile_photo_obj->path)) {
              $photo = '/storage/assets/photos/user-default.png';
          } else {
              $photo = $user->profile_photo_obj->path;
              if (Storage::disk('public')->exists($photo)) {
                  $photo = '/storage/' . $photo;
              } else {
                  $photo = '/storage/assets/photos/user-default.png';
              }
          }
        @endphp
        <img src="{{ $photo }}" class="card-img-top rounded-extra p-3" alt="user-image-{{ $user->id }}">
      </div>
      <div class="col-lg-8">
        <div class="card-body">
          <h5 class="card-title">
            <a class="signature" href="{{ url('user/' . $user->id) }}">{{ $user->name }} </a>
          </h5>
          <p class="card-text">{{ $user->bio }}</p>
          <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
          <a href="#" class="btn blue">Change photo</a>
        </div>
      </div>

      <div class="card-body">
        <form class="row g-3">
          <div class="col-lg-6">
            <label for="inputEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail" value="{{ $user->email }}">
          </div>

          <div class="col-lg-6 form-group">
            <label for="pass" class="form-label">Password</label>
            <div class="input-group mb-3">
              <input id="pass" type="password" class="form-control" aria-describedby="hide">
              <button id="hide" class="btn btn-outline-secondary" type="button">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <script src="{{ '/js/password.js' }}"></script>

          <div class="col-12">
            <label for="inputBio" class="form-label">Biography</label>
            <textarea class="form-control" id="inputBio" rows="4">{{ $user->bio }}</textarea>
          </div>
          <div class="col-12 text-end">
            <button type="submit" class="btn teal">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('aside')
  <div class="card mb-3">
    <div class="card-header text-white bg-petrol font-source-sans-pro rounded-top"> Support </div>
    <div class="card-body">
      <h5 class="card-title">Need help?</h5>
      <p class="card-text">Don't hesitate hitting us up.</p>
      <a href="{{ route('about') }}" class="btn blue">Contact us</a>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header text-white bg-petrol font-source-sans-pro rounded-top"> Other options </div>
    <div class="card-body">
      @auth
        <a href="{{ route('logout') }}" class="btn blue"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
      @endauth
    </div>
  </div>
@endsection