<div class="card">
  <img src="{{ '/images/morty.gif' }}" class="card-img-top rounded p-3" alt="profile-picture-{{ $user->name }}">
  <div class="card-body mb-3">
    <h4 class="card-title">{{ $user->name }}</h4>
    <p class="card-text">{{ $user->bio }}</p> {{-- I don't have a lot of experience with vampires, but I have hunted werewolves. I shot one once, but by the time I got to it, it had turned back into my neighbor's dog - Dwight --}}
  </div>

  <ul class="list-group list-group-flush">
    <li class="list-group-item">Reputation <strong class="float-end">{{ $user->reputation }}</strong></li>
    <li class="list-group-item">Joined <strong class="float-end">{{ $user->join_date }}</strong></li>
  </ul>

  <div class="card-body btn-group" role="group" aria-label="Second group">
    <a type="button" href="{{ url('user/' . $user->id . '/settings') }}" class="btn blue-alt">Edit Profile</a>
  </div>
</div>
