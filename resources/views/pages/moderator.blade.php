@extends('layouts.app', 
  [
    'user' => $user,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
      'comment.js',
      'app.js',
      'vote.js',
      'report.js',
      'follow.js',
      'save.js',
      'tag-moderate.js',
      'moderator-filters.js'
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
            <input type="text" id="user-search-input" class="form-control" placeholder="Username" aria-label="Username">
          </div>
        </form>
      </nav>
    </div>
    <div class="ban-users">
      @include('partials.search.user', [
        'users' => $displayed_users,
        'count' => $user_count,
        'page' => 1,
        'rpp' => 6,
        'user' => $user
      ])
    </div>
  </div>

  <div class="tag-area">
    <div class="tag-search">
      <nav class="tag-search-nav navbar navbar-light">
        <form class="container-fluid">
          <div class="input-group">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-tag text-dark-green"></i></span>
            <input type="text" id="tag-search-input" class="form-control" placeholder="Tag" aria-label="Tag">
          </div>
        </form>
      </nav>
    </div>
    <div class="moderate-tag container">
      @include('partials.search.tag-table', [
        'tags' => $displayed_tags,
        'count' => $tag_count,
        'page' => 1,
        'rpp' => 10,
        'user' => $user
      ])
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
