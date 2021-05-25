@extends('layouts.app', ['user' => $user])

@section('content')
  <div class="card mb-4 p-2-0 border-0 rounded">
    <div class="card-header bg-petrol text-white font-source-sans-pro">
      Ask a question
    </div>
    <div class="card-body">
      <form method="POST" action="{{ url('/api/question/insert') }}">
        <textarea class="form-control shadow-sm border border-2 bg-light mb-2" rows="1"
          placeholder="Question title"></textarea>
        <textarea class="form-control shadow-sm border border-2 bg-light mb-2" rows="5"
          placeholder="Question body"></textarea>
        <div class="row row-cols-auto mb-3">
          <div id="tags" class="col-lg-auto">
            <div class="btn-group mt-1" data-bs-toggle="collapse" data-bs-target="#addTag" aria-expanded="false"
              id="tag-select">
              <a class="btn blue-alt extra border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;add
                tag</a>
            </div>
            <div class="btn-group mt-1" data-bs-toggle="collapse" data-bs-target="#addTag" aria-expanded="false"
              id="tag-close">
              <a class="btn btn-danger border-0 my-btn-pad2"><i class="fas fa-window-close"></i>&nbsp;close</a>
            </div>
            <div class="tag-selected btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-minus-square"></i>&nbsp;java</a>
            </div>
            <div class="tag-selected btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-minus-square"></i>&nbsp;node</a>
            </div>
            <div class="tag-selected btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-minus-square"></i>&nbsp;msi</a>
            </div>
            <div class="tag-selected btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-minus-square"></i>&nbsp;nvidia</a>
            </div>
          </div>
          <input class="btn btn-success teal text-white ms-auto me-3 mt-1" type="submit" value="Submit" role="button" />
        </div>
      </form>

      <div class="card collapse" id="addTag" style="width: 18rem;">
        <div class="card-body">
          <input id="ask-search-tag" type="text" class="form-control shadow-sm border border-2 bg-light mb-2" rows="1"
            placeholder="Search tag" />
          <div id="tags" class="col-lg-auto">
            <div class="search-tag btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;unix</a>
            </div>
            <div class="search-tag btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;ps4</a>
            </div>
            <div class="search-tag btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;dell</a>
            </div>
            <div class="search-tag btn-group mt-1">
              <a class="btn blue-alt border-0 my-btn-pad2"><i class="fas fa-plus-square"></i>&nbsp;apple</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('aside')
  @include('partials.aside.rules')
  @include('partials.aside.info')
@endsection
