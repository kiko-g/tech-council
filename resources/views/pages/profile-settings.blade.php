@auth
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
        'edit-profile.js',
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
          <div class="card m-3 ms-0">
            <div class="card-header">Status</div>
            <div class="card-body">
              <h5 class="card-title">
                <a id="user-name" class="signature" href="{{ url('user/' . $user->id) }}">{{ $user->name }} </a>
              </h5>
              <p id="user-email" class="card-text pb-2 border-bottom"><strong>Email</strong>:&nbsp;{{ $user->email }}</p>
              <h5 class="card-title mt-1"><strong>Biography</strong></h5>
              <p id="user-biography" class="card-text">{{ $user->bio }}</p>
              {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --}}
              <button id="save-edit-{{ $user->id }}" class="btn wine mt-3 float-end">Request password change</button>
            </div>
          </div>
        </div>
        
        <div class="card-body bg-light">
          <form id="profile-settings-form" class="row g-3">
            <div class="col-12">
              <label class="mt-0 mb-1" for="inputImage">Change Photo</label>
              <input id="inputImage" type="file" class="form-control" accept="image/png" aria-label="profile picture" >
              <div class="invalid-feedback">Example invalid form file feedback</div>
            </div>

            <div class="col-lg-6">
              <label for="inputEmail" class="form-label mb-1">Email</label>
              <input id="inputEmail" type="email" class="form-control" value="{{ $user->email }}">
            </div>

            <div class="col-lg-6 form-group">
              <label for="inputUsername" class="form-label mb-1">Username</label>
              <input id="inputUsername" type="username" class="form-control" value="{{ $user->name }}">
            </div>
            {{-- <script src="{{ '/js/password.js' }}"></script> --}}

            <div class="col-12">
              <label for="inputBio" class="form-label mb-1">Biography</label>
              <textarea class="form-control" id="inputBio" rows="4">{{ $user->bio }}</textarea>
            </div>
          </form>
          <div class="col-12 text-end mt-3">
            <button id="save-edit-{{ $user->id }}" onclick="submitEditProfile()" type="submit" class="btn teal">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  @endsection

  @section('aside')
    @include('partials.aside.support')
    <div class="card mb-3">
      <div class="card-header text-white bg-petrol font-source-sans-pro bg-animated rounded-top"> Other options</div>
      <div class="card-body">
        <a href="{{ route('logout') }}" class="btn blue"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
      </div>
    </div>
  @endsection
@endauth