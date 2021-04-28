@extends('layouts.app')

@section('content')
  <div class="card mb-5 p-2-0 border-0 rounded">
    <div class="card-header bg-petrol text-white font-source-sans-pro">
      <a class="a header" href="../pages/question.php">
        {{ $question->title }}
        <i class="fas fa-link fa-xs text-blue-200 mt-1dot5 ms-2"></i>
      </a>
    </div>
    <div class="card-body" data-content-id="{{ $question->content_id }}">
      <article class="row row-cols-2 mb-1">
        <div class="col-auto flex-wrap">
          <div id="votes" class="votes btn-group-vertical mt-1 flex-wrap">
            <a id="upvote-button" class="upvote-button my-btn-pad up btn btn-outline-success teal"
              onclick="vote('up', this.parentNode)">
              <i class="fas fa-chevron-up"></i>
            </a>
            <a id="vote-ratio" class="vote-ratio btn my-btn-pad fake disabled"> 42 </a>
            <a id="downvote-button" class="downvote-button my-btn-pad down btn btn-outline-danger pink"
              onclick="vote('down', this.parentNode)">
              <i class="fas fa-chevron-down"></i>
            </a>
          </div>
        </div>

        <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0">
          <div id="question" class="mb-1">
            Section before <code>collapse</code>.

            <button class="btn btn-outline-info dark border-0 py-0 px-1" type="button" onclick="toogleText(this)"
              data-bs-toggle="collapse" data-bs-target="#collapseQuestionText2" aria-expanded="false"
              aria-controls="collapseQuestionText2">
              <i class="fas fa-ellipsis-h"></i>
            </button>

            <div class="collapse mt-2" id="collapseQuestionText2">
              {{ $question->content->main }}
            </div>
          </div>

          <div class="row row-cols-2 mb-1">
            <div id="interact" class="col-md flex-wrap">
              <div class="btn-group mt-1 rounded">
                <a class="upvote-button my-btn-pad2 btn btn-outline-success bookmark" id="upvote-button-<ID>"
                  onclick="toggleStar(this)" href="#">
                  <i class="far fa-bookmark"></i>&nbsp;Save
                </a>
              </div>
              @if ($question->comments == null)
                <div class="btn-group mt-1 rounded">
                  <a class="upvote-button btn teal my-btn-pad2" id="upvote-button-<ID>" href="#">
                    <i class="far fa-comment-dots"></i>&nbsp;25
                  </a>
                </div>
              @endif
              <div class="btn-group mt-1 rounded">
                <a class="upvote-button btn blue my-btn-pad2" id="upvote-button-<ID>" href="#">
                  <i class="fas fa-share-alt"></i>&nbsp;Share
                </a>
              </div>
            </div>

            <div id="tags" class="col-md-auto flex-wrap">
              @foreach ($question->tags as $tag)
                <div class="btn-group mt-1">
                  <a class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">{{ $tag }}</a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </article>

      @if ($comments != null)
        <?php buildCommentSection($comments, 1); ?>
      @endif
    </div>

    <div class="card-footer text-muted text-end p-0">
      <blockquote class="blockquote mb-0">
        <p class="card-text px-1">
          <small class="text-muted">asked {{ $question->content->creation_date }}</small>
          <small>
            <a class="signature" href="#">{{ $question->author_id; /* SHOULDNT BE AN ID */ }}</a>
          </small>
        </p>
      </blockquote>
    </div>
  </div>
@endsection

@section('aside')
  @include('partials.aside')
@endsection
