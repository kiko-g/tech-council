@php
$team_members = [
    'fiambre' => [
        'bio' => 'Francesinhas enthusiast',
        'social' => [
            'github' => 'https://github.com/ffriande',
            'twitter' => 'https://github.com/ffriande',
        ],
    ],
    'kikojpg' => [
        'bio' => 'Beer expert',
        'social' => [
            'github' => 'https://github.com/kiko-g',
            'twitter' => 'https://twitter.com/kikogoncalves_',
            'globe' => 'https://kiko-g.github.io',
        ],
    ],
    'jdiogueiro' => [
        'bio' => 'The next great Midfielder',
        'social' => [
            'github' => 'https://github.com/TsarkFC',
            'facebook' => 'https://github.com/TsarkFC',
        ],
    ],
    'mpinto01' => [
        'bio' => 'Bezo\'s pupil',
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
  <div id="description">
    <header class="text-light mb-2 mt-2">
      <h1>Tech Council</h1>
    </header>
    <header class="text-start text-light mb-3 ms-3">
      <h2>About</h2>
    </header>
    <div class="shadow p-3 mb-5 bg-light border border-5 rounded-3 fs-5 text-start">
      TechCouncil is a platform where users can post questions and share answers for everything tech-related, whether it's
      how to build a custom PC, what new smartphone is the best or how to install a VPN.
    </div>
  </div>

  <div id="team">
    <header class="text-start text-light mb-3 ms-3">
      <h2>Team</h2>
    </header>
    <div class="card-group">
      @foreach ($team_members as $name => $attrs)
        @php $member_counter++; @endphp
        <div class="card">
          <img src="{{ '/images/team' . $member_counter . '.jpeg' }}" class="card-img-top "
            alt="Team Member #{{ $member_counter }}">
          <div class="card-body">
            <h5 class="card-title">{{ $name }}</h5>
            <p class="card-text">{{ $attrs['bio'] }}</p>
          </div>
          <div class="card-footer">
            <small class="text-muted">
              @foreach ($attrs['social'] as $social_net => $link)
                <a href="{{ $link }}" class="btn btn blue btn-sm"> <i class="fa fa-{{ $social_net }}" aria-hidden="true"></i> </a>
              @endforeach
            </small>
          </div>
        </div>
      @endforeach
    </div>


    <div class="shadow p-3 mb-5 bg-light border border-5 rounded-3 fs-5 text-start">
      Any doubts contact us at <a href="techcouncilfeup@gmail.com">techcouncilfeup@gmail.com</a>
    </div>
  </div>


@endsection
