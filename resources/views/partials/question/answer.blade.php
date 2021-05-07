<div id="{{ 'answer-' . $answer->content_id }}"
   class="card mb-4 border-0 p-0 rounded bg-{{ $answer->is_best_answer ? 'teal-600' : 'background-color' }}">
  <div class="card m-1">
    <div class="card-body">
      <article class="row row-cols-3 mb-1" data-content-id="{{ $answer->content_id }}">
        <div class="col-auto flex-wrap">
          <div id="votes-{{ $answer->content_id }}" class="votes btn-group-vertical mt-1 flex-wrap">
            @php
              $upClass = 'teal';
              if($voteValue === 1) $upClass = 'active-teal';
              
              $downClass = 'pink';
              if($voteValue === -1) $downClass = 'active-pink';
            @endphp
            <a id="upvote-button-{{ $answer->content_id }}" class="upvote-button-answer my-btn-pad btn btn-outline-success {{ $upClass }}" data-content-id="{{ $answer->content_id }}">
              <i class="fas fa-chevron-up"></i>
            </a>
            <a id="vote-ratio-{{ $answer->content_id }}" class="vote-ratio-answer btn my-btn-pad fake disabled"> {{ $answer->votes_difference }} </a>
            <a id="downvote-button-{{ $answer->content_id }}" class="downvote-button-answer my-btn-pad btn btn-outline-danger {{ $downClass }}" data-content-id="{{ $answer->content_id }}">
              <i class="fas fa-chevron-down"></i>
            </a>
          </div>
        </div>

        <div class="col-9 col-sm-9 col-md-9 col-lg-9 flex-wrap pe-0 collapse show answer-collapse-{{ $answer->content_id }}">
          <div id="{{ 'answer-content-' . $answer->content_id }}" class="mb-1">
            <p>
              {!! $answer->content->main !!}
            </p>
          </div>
        </div>
        @auth
        @if (Auth::user()->id == $answer->content->author_id)
          <div class="col-2 p-0 m-0 collapse show answer-control answer-collapse-{{ $answer->content_id }}" id="answer-control-{{ $answer->content_id }}">
            <div class="btn-group float-end">
              <button class="btn p-0 answer-edit" id="answer-edit-{{ $answer->content_id }}" type="button" data-bs-toggle="collapse" data-bs-target=".answer-collapse-{{ $answer->content_id }}" aria-expanded="true" aria-controls="answer-content-{{ $answer->content_id }} answer-control-{{ $answer->content_id }}">
                <i class="fas fa-edit text-teal-300 mt-1 ms-2"></i>
              </button>
              
              <!-- Button trigger modal -->
              <button type="button" class="btn p-0 delete-answer-modal-trigger" data-bs-toggle="modal" data-bs-target="#delete-answer-modal-{{ $answer->content_id }}">
                <i class="fas fa-trash text-wine mt-1 ms-2"></i>
              </button>
            </div>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="delete-answer-modal-{{ $answer->content_id }}" tabindex="-1" aria-labelledby="delete-answer-modal-{{ $answer->content_id }}-label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-danger" id="delete-answer-modal-{{ $answer->content_id }}-label">Delete answer</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark">
                  Deleting answer to question: {{ $answer->question->title }}
                  <div class="alert alert-warning mt-2" role="alert">
                    Warning! This action is not reversible. The answer and associated comments will be permanently deleted.
                  </div>
                </div>
                <div class="modal-footer">
                  <form class="answer-delete" id="answer-delete-{{ $answer->content_id }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-success delete-modal" id="delete-answer-{{ $answer->content_id }}" data-bs-dismiss="modal" type="submit">
                      Delete
                    </button>
                  </form>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
        @endif
        @endauth

        @include('partials.question.edit-answer-form', ['answer' => $answer])

      </article>
      @include('partials.question.comment-section', ['comments' => $answer->comments, 'id' => $answer->content_id])
    </div>

    <div class="card-footer text-muted text-end p-0">
      <blockquote class="blockquote mb-0">
      <p class="card-text px-1"><small class="text-muted">asked Aug 14 2020 at 15:31&nbsp;<a class="signature" href="#">user</a></small></p>
      </blockquote>
    </div>
  </div>
</div>
