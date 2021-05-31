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
      <h4 class="card-title">{{ $user->name }}
        @if ($user->moderator)
          @include('partials.icons.moderator', ['width' => 25, 'height' => 25, 'title' => 'Moderator'])
        @endif
        @if($user->expert)
          @include('partials.icons.medal', ['width' => 25, 'height' => 25, 'title' => 'Expert User'])
        @endif
      </h4>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <p class="card-text text-start">Reputation <strong class="float-end">{{ $user->reputation }}</strong></p>
        </li>
        <li class="list-group-item mb-3">
          <p class="card-text text-start">Joined <strong class="float-end">{{ $user->join_date }}</strong></p>
        </li>
        @if ($user->banned)
          <a href="#" class="btn blue-alt">Unban</a>
        @else
          <a href="#" class="btn blue-alt">Ban</a>
        @endif
      </ul>      
    </div>
  </div>
</section>
