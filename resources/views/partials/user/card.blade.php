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
    <img src="{{ $photo }}" class="card-img-top user-img" alt="kermy">
    <div class="card-body">
      <h5 class="card-title">{{ $user->name }}</h5>
      <p class="card-text">Reputation: {{ $user->reputation }}</p>
      <p class="card-text">Joined: {{ $user->join_date }}</p>
      @if ($user->banned)
        <a href="#" class="btn blue-alt">Unban</a>
      @else
        <a href="#" class="btn blue-alt">Ban</a>
      @endif
    </div>
  </div>
</section>
