@extends('layouts.app')

{{-- @include('partials.question.card')
@include('partials.tag.card') --}}

@section('content')
  <div class="tab-content" id="nav-tabContent">
    <div class="search-results-header rounded">
      <main class="container pb-2">
        <div class="row justify-content-between search-and-pose">
          <div class="col-12 my-auto">
            <h5> Search Results for "windows" </h5>
            <h6> [163 results] </h6>
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
      <div>
        @foreach ($questions as $question)
          @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
        @endforeach
      </div>
    </div>

    {{--  TAGS --}}
    <div class="tab-pane fade" id="nav-tags" role="tabpanel">
      @include('partials.filters.tag')
      <div>
          @foreach ($tags as $tag)
            {{--  @include('partials.tag.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])  --}}
          @endforeach
      </div>
    </div>

    {{--  USERS  --}}
    <div class="tab-pane fade" id="nav-users" role="tabpanel">
      <div class="container">
        @php
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
                  @include('partials.user.card-simple')
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
