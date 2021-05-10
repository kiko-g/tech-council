@extends('layouts.app')

@section('content')
  <script src={{ '/js/moderator-switch.js' }} defer></script>
  <div class="users-tags-or-reports-picker">
    <div class="btn-group users-tags-or-reports-button" role="group">
      <button type="button" class="btn active blue-alt users-button">Users</button>
      <button type="button" class="btn blue-alt tags-button">Tags</button>
      <button type="button" class="btn blue-alt reports-button">Reports</button>
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
            @include('partials.user.card')
          </div>
        @endfor
      </div>
      <div class="row">
        @for ($i = 0; $i < 3; $i++)
          <div class="col-lg">
            @include('partials.user.card')
          </div>
        @endfor
      </div>
    </div>
    <div class="results-picker">
      @include('partials.pagination')
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
      @include('partials.tag.table')
    </div>
    <div class="results-picker">
      @include('partials.pagination')
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
  </div>
@endsection

@section('aside')
  @include('partials.aside')
@endsection
