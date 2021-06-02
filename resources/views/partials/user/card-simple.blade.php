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
    <img src="{{ $photo }}" id="user-img-{{ $user->id }}" class="card-img-top rounded-top mb-1" alt="user-profile-picture">
    <div class="card-body"> 
      <h4 class="card-title"><a href="{{ url('user/' . $user->id) }}">{{ $user->name }} </a>
        @if ($user->moderator)
          @include('partials.icons.moderator', ['width' => 25, 'height' => 25, 'title' => 'Moderator'])
        @endif
        @if($user->expert)
          @include('partials.icons.medal', ['width' => 25, 'height' => 25, 'title' => 'Expert User'])
        @endif
      </h4>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <p class="card-text text-start">{{ $user->bio }}</p>
        </li>
        <li class="list-group-item">
          <p class="card-text text-start">Reputation <strong class="float-end">{{ $user->reputation }}</strong></p>
        </li>
        <li class="list-group-item">
          <p class="card-text text-start">Joined <strong class="float-end">{{ $user->join_date }}</strong></p>
        </li>
      </ul>
    </div>
  </div>
</section>
