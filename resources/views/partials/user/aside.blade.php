<div class="card">
  <div class="card text-center">
    @php
      if(!isset($user->profile_photo_obj->path)) {
        $photo = '/storage/assets/photos/user-default.png';
      }
      else {
        $photo = $user->profile_photo_obj->path;
        if(Storage::disk('public')->exists($photo)) $photo = '/storage/' . $photo;
        else $photo = '/storage/assets/photos/user-default.png';
      }
    @endphp
    <img src="{{ $photo }}" class="card-img-top rounded p-3" alt="profile-picture-{{ $user->name }}">
    <div class="card-body pt-0">
      <h4 class="card-title mb-4">{{ $user->name }}</h4>
      <p class="card-text text-start">{{ $user->bio }}</p> 
      {{-- I don't have a lot of experience with vampires, but I have hunted werewolves. I shot one once, but by the time I got to it, it had turned back into my neighbor's dog - Dwight --}}
    </div>

    <ul class="list-group list-group-flush">
      <li class="list-group-item"><p class="card-text text-start">Reputation <strong class="float-end">{{ $user->reputation }}</strong></p></li>
      <li class="list-group-item"><p class="card-text text-start">Joined <strong class="float-end">{{ $user->join_date }}</strong></p></li>
    </ul>

    <div class="card-body btn-group" role="group" aria-label="Second group">
      <a type="button" href="{{ url('user/' . $user->id . '/settings') }}" class="btn blue-alt">Edit Profile</a>
    </div>
  </div>
