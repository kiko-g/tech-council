<div class="card">
  <div class="card text-center">
    @php
      if (!isset($user->profile_photo_obj->path)) {
          $photo = '/storage/assets/photos/user-default.png';
      } else {
          $photo = $user->profile_photo_obj->path;
          if (Storage::disk('public')->exists($photo)) {
              $photo = '/storage/' . $photo;
          } else {
              $photo = '/storage/assets/photos/user-default.png';
          }
      }
    @endphp
    <img src="{{ $photo }}" class="card-img-top rounded-extra p-3" alt="profile-picture-{{ $user->name }}">
    <div class="card-body pt-0">
      <h4 class="card-title"><a href="{{ url('user/' . $user->id) }}">{{ $user->name }} </a>
        @if ($user->moderator)
          @include('partials.icons.moderator', ['width' => 25, 'height' => 25, 'title' => 'Moderator'])
        @endif
        @if($user->expert)
          @include('partials.icons.medal', ['width' => 25, 'height' => 25, 'title' => 'Expert User'])
        @endif
        {{-- {!! '&nbsp;<i class="fas fa-briefcase fa-xs"></i>' !!} --}}
        {{-- {!! '&nbsp;<i class="fas fa-medal fa-xs"></i>' !!} --}}
      </h4>
    </div>

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

    
    @auth
      @php
        if (isset($user)) {
          if ($user->isReportedByUser()) {
            $report_class = 'active-report';
            $report_text = 'Reported';
            $report_availability = 'disabled';
          }
          else {  
            $report_class = 'report';
            $report_text = 'Report';
            $report_availability = '';
          }
        }
      @endphp

      <div class="card-body btn-group @if($user->id == Auth::user()->id) {{ 'pb-0' }} @endif" role="group" aria-label="Second group">
        <a type="button" href="mailto:{{ $user->email }}" class="btn blue-alt">Contact</a>
      </div>
      @if($user->id == Auth::user()->id)
        <div class="card-body btn-group @if(Auth::user()->moderator && !$user->moderator) {{ 'pb-0' }} @endif" role="group" aria-label="Second group">
          <a type="button" href="{{ url('user/' . $user->id . '/settings') }}" class="btn blue-alt">Edit Profile</a>
        </div>
      @endif
      <div class="card-body btn-group @if($user->id != Auth::user()->id) {{ 'pt-0' }} @endif" role="group" aria-label="Second group">
        <a type="button" class="btn wine {{ $report_class }} {{ $report_availability }}" id="user-report-button-{{ $user->id }}"
         data-bs-toggle="modal" data-bs-target="#user-report-modal-{{ $user->id }}"> {{ $report_text }}
        </a>
      </div>
      @include('partials.user-report-modal', [
        "user_id" => $user->id,
      ])
    @endauth
  </div>
