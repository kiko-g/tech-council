@extends('layouts.app', 
  [
    'user' => $user,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
      'ban.js',
      'comment.js',
      'app.js',
      'vote.js',
      'report.js',
      'follow.js',
      'save.js',
      'tag-moderate.js',
    ]
  ]
)

@section('content')
  <script src={{ '/js/moderator-switch.js' }} defer></script>
  <div class="users-tags-or-reports-picker">
    <div class="btn-group users-tags-or-reports-button" role="group">
      <button type="button" class="btn blue-alt users-button">Users</button>
      <button type="button" class="btn blue-alt tags-button">Tags</button>
      <button type="button" class="btn blue-alt reports-button active">Reports</button>
    </div>
  </div>

  <div class="user-area">
    <div class="user-search">
      <nav class="user-search-nav navbar navbar-light">
        <form class="container-fluid">
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-user text-teal-alt"></i></span>
            <input type="text" class="form-control" placeholder="Username" aria-label="Username">
          </div>
        </form>
      </nav>
    </div>
    <div class="ban-users">
      <div class="row">
        @for ($i = 0; $i < 3; $i++)
          <div class="col-lg">
            @if ($i < count($displayed_users))
              @include('partials.user.card', ['user' => $displayed_users[$i]])
            @endif
          </div>
        @endfor
      </div>
      <div class="row">
        @for ($i = 3; $i < 6; $i++)
          <div class="col-lg">
            @if ($i < count($displayed_users))
              @include('partials.user.card', ['user' => $displayed_users[$i]])
            @endif
          </div>
        @endfor
      </div>
    </div>
    <div class="results-picker">
      {{--@include('partials.pagination')--}}
    </div>
  </div>

  <div class="tag-area">
    <div class="tag-search">
      <nav class="tag-search-nav navbar navbar-light">
        <form class="container-fluid">
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-tag text-dark-green"></i></span>
            <input type="text" class="form-control" placeholder="Tag" aria-label="Tag">
          </div>
        </form>
      </nav>
    </div>
    <div class="moderate-tag container">
      @include('partials.tag.table', ['tags' => $displayed_tags])
    </div>
    <div class="results-picker">
      {{--@include('partials.pagination')--}}
    </div>
  </div>

  <div class="report-area">
    <div class="report-search">
      <nav class="report-search-nav navbar navbar-light py-0">
        <form class="container-fluid">
          @include('partials.filters.reports')
        </form>
      </nav>
    </div>

    <div id="user-reports">
      @include('partials.reports.user', ['user_reports' => $user_reports])
      {{--@include('partials.pagination')--}}
    </div>

    <div id="content-reports">
      @include('partials.reports.content', ['content_reports' => $content_reports])
      {{--@include('partials.pagination')--}}
    </div>

  </div>
@endsection

@section('aside')
  @include('partials.aside', ['user' => $user])
@endsection
