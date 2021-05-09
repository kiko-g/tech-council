<div class="card mb-5 p-2-0 border-0 rounded" id="{{ 'answer-' . $answer->content_id }}"> {{-- bg-{{ $answer->is_best_answer ? 'teal-600' : 'background-color' }} --}}
    <div class="card-header bg-petrol text-white font-source-sans-pro rounded-top"></div>
    <div class="card-body">
      <article class="row row-cols-3 mb-1 pe-1" data-content-id="{{ $answer->content_id }}">
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

        <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0">
          <div id="{{ 'answer-content-' . $answer->content_id }}" class="mb-1">
              {!! $answer->content->main !!}
          </div>
          @include('partials.question.comment-section', ['comments' => $answer->comments, 'id' => $answer->content_id])
        </div>

        @auth
        @if (Auth::user()->id == $answer->content->author_id)
          <div class="col-2 p-0 m-0 collapse show answer-control answer-collapse" id="answer-control-{{ $answer->content_id }}">
            <div class="btn-group float-end">
              <button class="btn p-0 answer-edit" id="answer-edit-{{ $answer->content_id }}" type="button" data-bs-toggle="collapse" data-bs-target=".answer-collapse" aria-expanded="true" aria-controls="answer-content-{{ $answer->content_id }} answer-control-{{ $answer->content_id }}">
                <i class="fas fa-edit text-teal-300 mt-1 ms-2"></i>
              </button>
              
              {{-- Button trigger modal --}}
              <button type="button" class="btn p-0 delete-answer-modal-trigger" data-bs-toggle="modal" data-bs-target="#delete-answer-modal-{{ $answer->content_id }}">
                <i class="fas fa-trash text-wine mt-1 ms-2"></i>
              </button>
            </div>
          </div>

          {{-- Modal --}}
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
                    Warning! Your answer will not be completed deleted so other people can still see its comments. Instead we will place [deleted] on its content.
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

        @auth
        {{-- edit form --}}
        <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0 collapse answer-collapse">
          <form class="answer-collapse container ps-0 answer-edit-form" id="answer-edit-form-{{ $answer->content_id }}" method="post">
            @method('PUT')
            @csrf
            <div class="row row-cols-2">
              {{-- edit text area --}}
              <div id="{{ 'answer-content-' . $answer->content_id }}" class="mb-1 col-10 me-auto p-0">
                <textarea id="answer-submit-input-{{ $answer->content_id }}" name="main" class="form-control shadow-sm border border-2 bg-light" rows="5" placeholder="Type your answer">
                  {!! $answer->content->main !!}
                </textarea>
              </div>

              {{-- form control buttons --}}
              @if (Auth::user()->id == $answer->content->author_id)
                <div class="col-1 p-0 m-0 collapse answer-control answer-collapse" id="answer-control-{{ $answer->content_id }}">
                  <div class="btn-group float-end">
                    <button class="btn p-0" type="submit">
                      <i class="fas fa-check text-teal-300 mt-1 ms-2"></i>
                    </button>
                    <button class="btn p-0" type="button" data-bs-toggle="collapse" data-bs-target=".answer-collapse" aria-expanded="true" aria-controls="answer-content-{{ $answer->content_id }} answer-control-{{ $answer->content_id }}">
                      <i class="fas fa-close text-wine mt-1 ms-2"></i>
                    </button>
                  </div>
                </div>
              @endif
            <div>
          </form>
        </div>
        @endauth
      </article>
    </div>
    <footer class="card-footer text-muted text-end p-0">
      <blockquote class="blockquote mb-0">
      <p class="card-text px-1 h6">
        <small class="text-muted">answered {{ $answer->content->creation_date }}</small>
        <small>
          <a class="signature" href="#">{{ $answer->content->author->name }}
            @if ($question->content->author->moderator)
              {!! '&nbsp;<i class="fas fa-briefcase fa-sm"></i>' !!}
            @elseif($question->content->author->expert)
              {!! '&nbsp;<i class="fas fa-medal fa-sm"></i>' !!}
            @endif
          </a>
        </small>
      </p>
      </blockquote>
    </footer>
</div>