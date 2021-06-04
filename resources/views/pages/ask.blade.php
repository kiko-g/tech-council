@extends('layouts.app', 
  [
    'user' => $user,
    'js' => [
      'input.js',
      'components.js',
      'question.js',
      'app.js',
      'vote.js',
      'follow.js',
      'save.js',
      'tag-search.js',
    ]
  ]
)

@section('content')
  <div class="card mb-4 p-2-0 border-0 rounded">
    <header class="card-header bg-petrol text-white font-source-sans-pro rounded-top"> Ask a question </header>
    <section class="card-body">
      <form method="POST" action="{{ url('/api/question/insert') }}">
        <div>
          <textarea id="input-title" class="form-control shadow-sm border border-2 bg-light mb-2" rows="1" placeholder="Question title"></textarea>
        </div>
        <div class="textarea-container">
          <textarea id="input-body" class="form-control shadow-sm border border-2 bg-light mb-2" rows="8" placeholder="Question body"></textarea>
          <button id="toggle-stackedit" class="btn btn blue toggle-stackedit off" type="button" data-bs-original-title="Switch to stackedit">StackEdit</button>
        </div>
        
        <script src="{{ '/js/stackedit.js' }}" defer> </script>
        <script src={{ '/js/question-edit.js' }} defer></script>
        <div class="row row-cols-auto mb-3">
          <div id="tags" class="col-lg-auto">
            <div id="tag-select" class="btn-group mt-1" data-bs-toggle="collapse" data-bs-target="#addTag" aria-expanded="false">
              <a class="btn blue-alt extra border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;add tag</a>
            </div>
            <div class="btn-group mt-1" data-bs-toggle="collapse" data-bs-target="#addTag" aria-expanded="false" id="tag-close">
              <a class="btn btn-danger wine border-0 my-btn-pad2"><i class="fas fa-window-close"></i>&nbsp;close</a>
            </div>
            <div id="ask-selected-tags">
              <!--
              <div class="tag-selected btn-group mt-1">
                <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-minus-square"></i>&nbsp;node</a>
              </div>
              -->
            </div>
          </div>
          <input class="btn btn-success teal text-white ms-auto me-3 mt-1" type="submit" value="Submit" role="button" />
        </div>

        {{-- Select tags --}}
        <div class="card collapse" id="addTag" style="width: 18rem;">
          <div class="card-body">
            <input id="ask-search-tag" type="text" class="form-control shadow-sm border border-2 bg-light mb-2" rows="1"
              placeholder="Search tag" />
            <div id="ask-tag-search-results" class="col-lg-auto">
              <!--
              <div class="search-tag btn-group mt-1">
                <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;unix</a>
              </div>
              <div class="search-tag btn-group mt-1">
                <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;ps4</a>
              </div>
              -->
            </div>
          </div>
        </div>
      </form>
    </section>
  </div>

  <script src="{{ '/js/toasts.js' }}" defer></script>
  <div class="toast-container pb-4" id="toastPlacement" data-original-class="toast-container position-absolute p-3">
    <div id="toast-tip-1" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
          aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
          <rect width="100%" height="100%" fill="#007aff"></rect>
        </svg>
        <strong class="me-auto">Friendly Tip</strong>
        <button type="button" class="btn-close" onclick="hideToast()" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body"> 
        We recommend trying the
        <a id="toggle-stackedit-tip" class="" type="button" data-bs-original-title="Switch to stackedit">StackEdit editor</a>
        to edit your question!
      </div>
    </div>
  </div>
@endsection

@section('aside')
  @include('partials.aside.rules')
  @include('partials.aside.info')
@endsection
