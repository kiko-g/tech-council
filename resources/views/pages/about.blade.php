@php
$team_members = [
    'fiambre' => [
        'bio' => 'Team member bio 1',
        'social' => [
            'github' => 'https://github.com/ffriande',
            'twitter' => 'https://github.com/ffriande',
        ],
    ],
    'kikojpg' => [
        'bio' => 'Team member bio 2',
        'social' => [
            'github' => 'https://github.com/kiko-g',
            'twitter' => 'https://twitter.com/kikogoncalves_',
            'globe' => 'https://kiko-g.github.io',
        ],
    ],
    'jdiogueiro' => [
        'bio' => 'Team member bio 3',
        'social' => [
            'github' => 'https://github.com/TsarkFC',
            'facebook' => 'https://github.com/TsarkFC',
        ],
    ],
    'mpinto01' => [
        'bio' => 'Team member bio 4',
        'social' => [
            'github' => 'https://github.com/MiguelDelPinto',
            'twitter' => 'https://github.com/MiguelDelPinto',
        ],
    ],
];

$member_counter = 0;
@endphp

@extends('layouts.entry')

@section('content')
  <header class="text-light mb-5">
    <h1>Tech Council</h1>
  </header>
  <div id="description">
    <header class="text-start text-light mb-4 ms-4">
      <h2>About</h2>
    </header>
    <div class="shadow p-3 mb-5 bg-light border border-5 rounded-3 fs-5 text-start">
      TechCouncil is a platform where users can post questions and share answers for everything tech-related, whether it's
      how to build a custom PC, what new smartphone is the best or how to install a VPN.
    </div>
  </div>
  <div id="team">
    <header class="text-start text-light mb-4 ms-4">
      <h3>Team</h3>
    </header>

    <div class="d-flex team-cards justify-content-center">
      @foreach ($team_members as $name => $attrs)
        @php $member_counter++; @endphp
        <div class="card me-3 mb-2 border border-5 rounded-3" style="width: 18rem;">
          <img src="{{ '/images/team' . $member_counter . '.jpeg' }}" class="card-img-top img-fluid"
            alt="Team member number {{ $member_counter }}">
          <div class="card-body">
            <h5 class="card-title">{{ $name }}</h5>
            <p class="card-text">{{ $attrs['bio'] }}</p>
            @foreach ($attrs['social'] as $social_net => $link)
              <a href="{{ $link }}" class="btn btn-primary"><i class="fa fa-{{ $social_net }}"
                  aria-hidden="true"></i></a>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
