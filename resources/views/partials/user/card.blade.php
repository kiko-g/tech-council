<section class="user-card rounded">
  <div class="card text-center">
    @php
      $photo = $user->profile_photo_obj->path;
      if (Storage::disk('public')->exists($photo)) $photo = '/storage/' . $photo;
      else $photo = '/storage/assets/photos/user-default.png';
    @endphp
    <img src="{{ $photo }}" class="card-img-top user-img" alt="kermy">
    <div class="card-body">
      <h5 class="card-title">{{ $user->name }}</h5>
      <p class="card-text">Banned: {{ $user->banned }}</p>
      <p class="card-text">Reputation: {{ $user->reputation }}</p>
      <p class="card-text">Joined: {{ $user->join_date }}</p>
      <a href="#" class="btn blue-alt">Ban</a>
    </div>
  </div>
</section>
