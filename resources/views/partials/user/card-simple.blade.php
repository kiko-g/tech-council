<section class="user-card rounded">
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
    <img src="{{ $photo }}" class="card-img-top user-img rounded-top mb-1" alt="user-profile-picture">
    <div class="card-body py-2 px-3"> 
      <h5 class="card-title border-bottom pb-2">{{ $user->name }}</h5>
      <p class="card-text m-0 text-start">Reputation: <strong>{{ $user->reputation }}</strong></p>
      <p class="card-text m-0 text-start">Joined: <strong>{{ $user->join_date }}</strong></p>
    </div>
  </div>
</section>
