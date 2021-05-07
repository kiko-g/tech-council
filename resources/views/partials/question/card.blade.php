<div class="card mb-5 p-2-0 border-0 rounded" id="{{ 'question-' . $question->content_id }}">
  <div class="card-header bg-petrol text-white font-source-sans-pro">
    <div class="row">
      <div class="col-auto me-auto">
        <a class="a header" href="{{ url('question/' . $question->content_id) }}">
          {{ $question->title }}
          <i class="fas fa-link fa-xs text-blue-200 mt-1dot5 ms-2"></i>
        </a>
      </div>
      @auth
        @if (Auth::user()->id == $question->content->author_id)
          <div class="col-auto btn-group">
            <button class="btn p-0" type="submit">
              <i class="fas fa-edit text-teal-300 mt-1 ms-2"></i>
            </button>
            <!-- Button trigger modal -->
            <button type="button" class="btn p-0 delete-question-modal-trigger" data-bs-toggle="modal"
              data-bs-target="#delete-question-modal-{{ $question->content_id }}">
              <i class="fas fa-trash text-wine mt-1 ms-2"></i>
            </button>
          </div>

          @include('partials.question.delete-modal', [
            "type" => "question",
            "content_id" => $question->content_id,
            "title" => $question->title,
            "redirect" => $include_comments
          ])
        @endif
      @endauth
    </div>
  </div>

  <div class="card-body" data-content-id="{{ $question->content_id }}">
    <article class="row row-cols-2 mb-1">
      <div class="col-auto flex-wrap">
        <div id="votes-{{ $question->content_id }}" class="votes btn-group-vertical mt-1 flex-wrap">
          @php
            $upClass = 'teal';
            if ($voteValue === 1) {
                $upClass = 'active-teal';
            }
            
            $downClass = 'pink';
            if ($voteValue === -1) {
                $downClass = 'active-pink';
            }
          @endphp
          <a id="upvote-button-{{ $question->content_id }}"
            class="upvote-button-question my-btn-pad up btn btn-outline-success {{ $upClass }}"
            data-content-id="{{ $question->content_id }}">
            <i class="fas fa-chevron-up"></i>
          </a>
          <a id="vote-ratio-{{ $question->content_id }}"
            class="{{ $voteValue }} vote-ratio-question btn my-btn-pad fake disabled">
            {{ $question->votes_difference }} </a>
          <a id="downvote-button-{{ $question->content_id }}"
            class="downvote-button-question my-btn-pad down btn btn-outline-danger {{ $downClass }}"
            data-content-id="{{ $question->content_id }}">
            <i class="fas fa-chevron-down"></i>
          </a>
        </div>
      </div>

      <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0">
        <div id="{{ 'question-content-' . $question->content_id }}" class="mb-1">

          {!! $question->content->main !!}
          {{-- <div class="collapse mt-2" id="collapseQuestionText2">
              Section inside <code>collapse</code>.
            </div> --}}

          {{-- <button class="btn btn-outline-info dark border-0 py-0 px-1" type="button" onclick="toogleText(this)"
              data-bs-toggle="collapse" data-bs-target="#collapseQuestionText2" aria-expanded="false"
              aria-controls="collapseQuestionText2">
              <i class="fas fa-ellipsis-h"></i>
            </button> --}}

        </div>

        <div class="row row-cols-2 mb-1">
          <div id="interact" class="col-md flex-wrap">
            <div class="btn-group mt-1 rounded">
              <a class="star-button my-btn-pad2 btn btn-outline-success bookmark"
                id="star-button-{{ $question->content_id }}" onclick="toggleStar(this)" href="#">
                <i class="far fa-bookmark"></i>&nbsp;Save
              </a>
            </div>
            @if (!$include_comments)
              <div class="btn-group mt-1 rounded">
                <a class="comment-number-button btn teal my-btn-pad2"
                  id="comment-number-button-{{ $question->content_id }}" href="#">
                  <i class="far fa-comment-dots"></i>&nbsp;25
                </a>
              </div>
            @endif
            <div class="btn-group mt-1 rounded">
              <a class="share-button btn blue my-btn-pad2" id="share-button-{{ $question->content_id }}" href="#">
                <i class="fas fa-share-alt"></i>&nbsp;Share
              </a>
            </div>
          </div>

          <div id="tags" class="col-md-auto flex-wrap">
            @foreach ($question->tags as $tag)
              <div class="btn-group mt-1">
                <a class="btn blue-alt border-0 my-btn-pad2" href="#">{{ $tag->name }}</a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </article>

    @if ($include_comments)
      @include('partials.question.comment-section', ['comments' => $question->comments, 'id' => $question->content_id])
    @endif
  </div>

  <div class="card-footer text-muted text-end p-0">
    <blockquote class="blockquote mb-0">
      <p class="card-text px-1">
        <small class="text-muted">asked {{ $question->content->creation_date }}</small>
        <small>
          <a class="signature" href="#">{{ $question->content->author_id }}</a>
        </small>
      </p>
    </blockquote>
  </div>
</div>
