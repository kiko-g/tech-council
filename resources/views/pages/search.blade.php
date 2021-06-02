@extends('layouts.app', 
  [
    'user' => $user,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
      'app.js',
      'vote.js',
      'report.js',
      'follow.js',
      'save.js',
      'tag-search.js',
      'tag-moderate.js',
    ]
  ]
)

@php
  if (strlen($query_string) > 30) {
      $results_for = "Search Results for \"" . substr("$query_string", 0, 27) . "...\"";
  } else {
    $results_for = "Search Results for \"" . $query_string . "\"";
  }

  $total_results = count($questions) + count($tags) + count($users);
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
      @include('partials.filters.question')
      <section id="search-tag-results">
        @foreach ($questions as $question)
          @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
        @endforeach
      </section>
    </div>

    {{--  TAGS --}}
    <div class="tab-pane fade" id="nav-tags" role="tabpanel">
      @include('partials.filters.tag')
        <section id="search-tag-results">
          @foreach ($tags as $tag)
            @include('partials.tag.card', ['tag' => $tag, 'user' => $user])
          @endforeach
        </section>
    </div>

    {{--  USERS  --}}
    <div class="tab-pane fade special-bg p-1 rounded" id="nav-users" role="tabpanel">
      <div class="container p-0">
        @php
          use App\Models\User;
          $user_amount = count($users);
          $rows_amount = ceil($user_amount / 3);
        @endphp
        @for($row = 0; $row < $rows_amount; $row++)
          <div class="row">
            @php
              $cols = $user_amount - ($row * 3);
              if($cols > 3) $cols = 3;
            @endphp
            @for($i = 0; $i < 3; $i++)
              <div class="col">
                @if($i < $cols)
                  @include('partials.user.card-simple', ['user' => $users[$i]])
                @endif
              </div>
            @endfor
          </div>
        @endfor
      </div>
      @include('partials.pagination')
    </div>
  </div>
@endsection

@section('aside')
  @include('partials.aside')
@endsection
