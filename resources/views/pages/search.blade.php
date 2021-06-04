@extends('layouts.app', 
  [
    'user' => $user,
    'search_string' => $query_string,
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
      'tag-search.js',
      'tag-moderate.js',
      'filters.js',
      'search.js'
    ]
  ]
)

@php
  if (strlen($query_string) > 30) {
      $results_for = "Search Results for \"" . substr("$query_string", 0, 27) . "...\"";
  } else {
    $results_for = "Search Results for \"" . $query_string . "\"";
  }

  $total_results = $question_count + $tag_count + $user_count;
  $results_num = "[" . $total_results . " result" . ($total_results !== 1 ? "s]" : "]");
@endphp

@section('content')
  <div class="tab-content" id="nav-tabContent">
    <div class="search-results-header rounded">
      {{-- NAVIGATION --}}
      <main class="container pb-2">
        <div class="row justify-content-between search-and-pose">
          <div class="col-12 my-auto">
            <h5> {{ $results_for }} </h5>
            <h6> {{ $results_num }} </h6>
          </div>
        </div>
        <nav>
          <div class="btn-group nav nav-tabs search-tabs" id="nav-tab" role="tablist">
            <button class="btn blue-alt users-button active" id="nav-questions-tab" data-bs-toggle="tab"
              data-bs-target="#nav-questions" type="button" role="tab">Questions</button>
            <button class="btn blue-alt tags-button" id="nav-tags-tab" data-bs-toggle="tab" data-bs-target="#nav-tags"
              type="button" role="tab">Tags</button>
            <button class="btn blue-alt reports-button" id="nav-users-tab" data-bs-toggle="tab"
              data-bs-target="#nav-users" type="button" role="tab">Users</button>
          </div>
        </nav>
      </main>
    </div>

    {{--  QUESTIONS --}}
    <div class="tab-pane fade show active" id="nav-questions" role="tabpanel">
      @include('partials.filters.question', ['filter_prefix' => "question_search"])
      @include('partials.search.question', [
        'questions' => $questions,
        'count' => $question_count,
        'page' => 1,
        'rpp' => 6
      ])
    </div>

    {{--  TAGS --}}
    <div class="tab-pane fade" id="nav-tags" role="tabpanel">
      @include('partials.filters.tag', ['filter_prefix' => "tag_search"])
      @include('partials.search.tag', [
        'tags' => $tags,
        'count' => $tag_count,
        'page' => 1,
        'rpp' => 6,
        'user' => $user
      ])
    </div>

    {{--  USERS  --}}
    <div class="tab-pane fade special-bg p-1 rounded" id="nav-users" role="tabpanel">
      <div class="container p-0">
        @include('partials.search.user', [
          'users' => $users,
          'count' => $user_count,
          'page' => 1,
          'rpp' => 6,
          'user' => $user
        ])
      </div>
    </div>
  </div>
@endsection

@section('aside')
  @include('partials.aside')
@endsection
