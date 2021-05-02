<header>
  <nav class="bg-animated navbar navbar-expand-lg navbar-dark bg-dark p-2">
    <div class="container-fluid justify-content-center px-3">
      <a class="navbar-brand" href="{{ route('home') }}">
        @include('partials.icon')
        <!-- <img src="/images/icon.png" alt="" width="23" height="23" class="d-inline-block align-top mt-1"> -->
        Tech Council
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse border-left-0" id="navbarNav">
        <form class="btn-group d-flex my-2" action="../pages/search.php">
          <input class="search-focus rounded-end must form-control bg-light border-0" type="search" placeholder="Search"
            aria-label="Search">
          <button class="btn btn-outline-light blue" type="submit" id="button-addon2"><i
              class="fas fa-search fa-sm"></i></button>
        </form>

        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-black">
          @auth
            @if ($user->moderator)
              <li class="nav-item">
                <a class="nav-link hover-cute btn btn-outline-light btn-sm border-0" href="{{ route('home') }}"><i
                    class="fas fa-briefcase fa-sm"></i>&nbsp;Moderator</a>
              </li>
            @endif
            <li class="nav-item">
              <a href="{{ route('logout') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0"
                type="submit"><i class="fas fa-sign-out-alt fa-sm"></i>&nbsp;Logout</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle btn btn-outline-light nohover btn-sm border-0" data-bs-toggle="dropdown"
                href="#" role="button" aria-expanded="false">
                <i class="fas fa-envelope fa-sm"></i>&nbsp;Inbox
                <span class="badge stack align-middle">24</span>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Separated link</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link hover-cute btn btn-outline-light btn-sm border-0" type="submit">
                Profile
                <img src="/images/dwight.png" alt="" width="23" height="23" class="">
              </a>
            </li>
          @else
            <li class="nav-item">
              <a href="{{ route('login') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0"
                type="submit"><i class="fas fa-sign-in-alt fa-sm"></i>&nbsp;Login</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('register') }}" class="nav-link hover-cute btn btn-outline-light btn-sm border-0"
                type="submit"><i class="fas fa-user-plus fa-sm"></i>&nbsp;Register</a>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>
</header>
