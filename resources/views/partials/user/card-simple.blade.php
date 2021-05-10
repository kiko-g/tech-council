<section class="user-card rounded">
  <div class="card text-center">
    <img src="images/dwight.png" class="card-img-top user-img" alt="kermy">
    <div class="card-body"> 
      {{-- TODO: pass user to --}}
      <h5 class="card-title">{{ $user->name }}</h5>
      <p class="card-text">Reputation: {{ $user->reputation }}</p>
      <p class="card-text">Joined: {{ $user->join_date }}</p>
    </div>
  </div>
</section>
