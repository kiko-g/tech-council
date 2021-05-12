@extends('layouts.app')

@section('content')
  <div class="card mb-3">
    <div class="card-header text-white bg-petrol font-source-sans-pro rounded-top"> User Settings </div>
    <div class="row g-0">
      <div class="col-lg-4">
        <img src="{{ '/images/morty.gif' }}" class="card-img-top rounded p-3" alt="user-image-{{ $user->id }}">
      </div>
      <div class="col-lg-8">
        <div class="card-body">
          <h5 class="card-title">{{ $user->name }}</h5>
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
      <a href="#" class="btn blue">Contact us</a>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header text-white bg-petrol font-source-sans-pro rounded-top"> Other options </div>
    <div class="card-body">
      <a href="#" class="btn blue"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
    </div>
  </div>
@endsection

{{-- <div class="col-12">
  <label for="inputAddress" class="form-label">Address</label>
  <input type="text" class="form-control" id="inputAddress" placeholder="Apartment, studio, or floor">
</div>
<div class="col-lg-6">
  <label for="inputCity" class="form-label">City</label>
  <input type="text" class="form-control" id="inputCity">
</div>
<div class="col-lg-4">
  <label for="inputState" class="form-label">State</label>
  <select id="inputState" class="form-select">
    <option selected>Choose...</option>
    <option>...</option>
  </select>
</div>
<div class="col-lg-2">
  <label for="inputZip" class="form-label">Zip</label>
  <input type="text" class="form-control" id="inputZip">
</div>
<div class="col-12">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" id="gridCheck">
    <label class="form-check-label" for="gridCheck">
      Check me out
    </label>
  </div>
</div> --}}
