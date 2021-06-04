<header>
  <nav class="bg-animated navbar navbar-expand-lg navbar-dark bg-dark p-2">
    <div class="container-fluid justify-content-left px-3">
      <a class="navbar-brand me-5" href="{{ route('home') }}"> @include('partials.icons.icon')Tech Council</a>
      <button class="navbar-toggler ms-5" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse border-left-0" id="navbarNav">
        <form class="btn-group d-flex my-2" action="{{ route('search') }}">
          <input class="search-focus rounded-end must form-control bg-light border-0" type="q" placeholder="Search" aria-label="Search" name="q">
          <button class="btn btn-outline-light blue" type="submit" id="button-addon2"><i class="fas fa-search fa-sm"></i></button>
        </form>

        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-black">
          @auth
            <li class="nav-item dropdown dropstart">
              <a class="nav-link dropdown-toggle btn btn-outline-light nohover btn-sm border-0" data-bs-toggle="dropdown" role="button" aria-expanded="false">
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
                <img src="{{ $photo }}" class="rounded" alt="profile-image-{{ $user->id }}" width="30" height="30"> 
              </a>
              <ul class="dropdown-menu profile">
                @if ($user->moderator)
                <li> <a class="dropdown-item" href="{{ url('moderator') }}">Moderator&nbsp;<i class="fas fa-briefcase fa-sm"></i></a></li>
                <li><hr class="dropdown-divider"></li>
                @endif
                <li><a class="dropdown-item" href="{{ url('user/' . $user->id) }}">Profile&nbsp;<i class="fas fa-address-card"></i></a></li>
                <li><a class="dropdown-item" href="{{ url('user/' . $user->id . '/settings') }}">Settings&nbsp;<i class="fas fa-cog fa-sm"></i></a></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout&nbsp;&nbsp;<i class="fas fa-sign-out-alt fa-sm"></i></a> </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ url('user/' . $user->id) }}">Ask Question&nbsp;<i class="fas fa-plus-square fa-sm"></i></a></li>
                {{-- <li><a class="dropdown-item" href="#">Saved Items&nbsp;<i class="fa fa-bookmark fa-sm"></i></a></li> --}}
                {{-- <li><a class="dropdown-item" href="#">Unread Posts&nbsp;<span class="badge align-middle">24</span></a></li> --}}
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('faq') }}">FAQ&nbsp;<i class="fas fa-question-circle fa-sm"></i></a></li>
                <li><a class="dropdown-item" href="{{ route('about') }}">About&nbsp;<i class="fas fa-info-circle fa-sm"></i></a></li>
              </ul>
            </li>
            @else
            <li class="nav-item"> <a href="{{ route('login') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-sign-in-alt fa-sm"></i>&nbsp;Login</a> </li>
            <li class="nav-item"> <a href="{{ route('register') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-user-plus fa-sm"></i>&nbsp;Register</a> </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>
</header>
