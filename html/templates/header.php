<header>
  <nav class="home-header navbar navbar-expand-lg navbar-dark bg-dark p-2">
    <div class="container-fluid justify-content-center px-3">
      <a class="navbar-brand" href="/">
        <img src="/images/icon.png" alt="" width="23" height="23" class="d-inline-block align-top mt-1">
        Tech Council
      </a>
      <div class="nav-item dropdown">
        <a class="nav-link text-white dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"> </a>
        <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
          <li><a class="dropdown-item" href="#">Action</a></li>
          <li><a class="dropdown-item" href="#">Another action</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
      </div>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse border-left-0" id="navbarNav">
        <form class="btn-group d-flex my-2" action="../pages/search.php">
          <input class="rounded-end must form-control bg-light border-0" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-light blue" type="submit" id="button-addon2"><i class="fas fa-search"></i></button>
        </form>

        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="/pages/login.php" class="btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-tags"></i>&nbsp;Tags</a>
          </li>
          <li class="nav-item">
            <a href="/pages/moderator.php" class="btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-briefcase"></i>&nbsp;Admin</a>
          </li>
          <li class="nav-item">
            <a href="/pages/login.php" class="btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-sign-in-alt"></i>&nbsp;Login</a>
          </li>
          <li class="nav-item">
            <a href="/pages/register.php" class="btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-user-plus"></i>&nbsp;Register</a>
          </li>
          <li class="nav-item">
            <a href="/pages/login.php" class="btn btn-outline-light btn-sm border-0" type="submit"><i class="fas fa-inbox"></i>&nbsp;Inbox
              <span class="badge align-middle">9</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="/pages/profile.php" class="btn btn-outline-light btn-sm border-0" type="submit">
              <!-- if signed in show -->
              <!-- <i class="fas fa-address-card"></i> -->
              <img src="/images/team4.jpeg" alt="" width="23" height="23" class="">&nbsp;Profile
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>