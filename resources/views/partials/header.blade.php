<header>
  <nav class="bg-animated navbar navbar-expand-lg navbar-dark bg-dark p-2">
    <div class="container-fluid justify-content-center px-3">
      <a class="navbar-brand me-5" href="{{ route('home') }}"> @include('partials.icon')Tech Council</a>
      <button class="navbar-toggler ms-5" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse border-left-0" id="navbarNav">
        <form class="btn-group d-flex my-2" action="{{ route('home') }}">
          <input class="search-focus rounded-end must form-control bg-light border-0" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-light blue" type="submit" id="button-addon2"><i class="fas fa-search fa-sm"></i></button>
        </form>

        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-black">
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle btn btn-outline-light nohover btn-sm border-0" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                <img src="/images/dwight.png" class="rounded" alt="profile-image-{{ $user->id }}" width="20" height="20"> 
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Unread Posts&nbsp;<span class="badge align-middle">24</span></a></li>
                <li><a class="dropdown-item" href="#">Saved Items&nbsp;<i class="fa fa-bookmark fa-sm"></i></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ url('user/' . $user->id) }}">Profile&nbsp;<i class="fas fa-address-card"></i></a></li>
                <li><a class="dropdown-item" href="{{ url('user/' . $user->id . '/settings') }}">Profile Settings&nbsp;<i class="fas fa-tools fa-sm"></i></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout&nbsp;<i class="fas fa-sign-out-alt fa-sm"></i></a> </li>
              </ul>
            </li>
            {{-- <li class="nav-item"> <a href="{{ route('home') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-envelope fa-sm"></i>&nbsp;Inbox&nbsp;<span class="badge stack align-middle">24</span> </a> </li> --}}
            @if ($user->moderator)
              <li class="nav-item"> <a class="nav-link hover-cute btn btn-outline-light btn-sm border-0" href="{{ url('moderator') }}"> <i class="fas fa-briefcase fa-sm"></i>&nbsp;Moderator</a> </li>
            @endif
            <li class="nav-item"> <a href="{{ url('user/' . $user->id) }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-address-card"></i>&nbsp;Profile</a></li>
            @else
            <li class="nav-item"> <a href="{{ route('login') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-sign-in-alt fa-sm"></i>&nbsp;Login</a> </li>
            <li class="nav-item"> <a href="{{ route('register') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-user-plus fa-sm"></i>&nbsp;Register</a> </li>
          @endauth
          <li class="nav-item"> <a href="{{ route('about') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-info-circle fa-sm"></i>&nbsp;About</a></li>
          <li class="nav-item"> <a href="{{ route('faq') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit"> <i class="fas fa-question-circle fa-sm"></i>&nbsp;FAQ</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>
