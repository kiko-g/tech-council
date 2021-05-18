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
            <div class="mt-3 rounded">
              <a class="float-end report-button btn red my-btn-pad2" id="report-button-{{ $answer->content_id }}"
                  data-bs-toggle="modal" data-bs-target="#report-modal-answer-{{ $answer->content_id }}">
                <i class="fas fa-flag"></i>
              </a>
            </div>
            @include('partials.report-modal', [
              "type" => "answer",
              "content_id" => $answer->content_id,
            ])
          </div>
        </div>

        <div class="col-9 col-sm-9 col-md-9 col-lg-9 flex-wrap pe-0 collapse show answer-collapse-{{ $answer->content_id }}">
          <div id="{{ 'answer-content-' . $answer->content_id }}" class="mb-1">
              {!! $answer->content->main !!}
          </div>
        </div>

        @auth
        @if (Auth::user()->id == $answer->content->author_id)
          <div class="col-2 p-0 m-0 collapse show answer-control answer-collapse-{{ $answer->content_id }}" id="answer-control-{{ $answer->content_id }}">
            <div class="btn-group float-end">
              <button class="btn p-0 answer-edit" id="answer-edit-{{ $answer->content_id }}" type="button" data-bs-toggle="collapse" data-bs-target=".answer-collapse-{{ $answer->content_id }}" aria-expanded="true" aria-controls="answer-content-{{ $answer->content_id }} answer-control-{{ $answer->content_id }}">
                <i class="fas fa-edit text-teal-300 mt-1 ms-2"></i>
              </button>
              
              {{-- Button trigger modal --}}
              <button type="button" class="btn p-0 delete-answer-modal-trigger" data-bs-toggle="modal" data-bs-target="#delete-answer-modal-{{ $answer->content_id }}">
                <i class="fas fa-trash text-wine mt-1 ms-2"></i>
              </button>
            </div>
          </div>

          @include('partials.question.delete-modal', [
            "type" => "answer",
            "content_id" => $answer->content_id,
            "title" => $answer->question->title,
            "redirect" => false
          ])
        @endif
        @endauth
        @include('partials.question.edit-answer-form', ['answer' => $answer])
      </article>
      @include('partials.question.comment-section', ['comments' => $answer->comments, 'id' => $answer->content_id])
    </div>
    <footer class="card-footer text-muted text-end p-0">
      <blockquote class="blockquote mb-0">
      <p class="card-text px-1 h6">
        <small class="text-muted">answered {{ $answer->content->creation_date }}</small>
        <small>
          <a class="signature" href="#">{{ $answer->content->author->name }}
            @if ($answer->content->author->moderator)
              {!! '&nbsp;<i class="fas fa-briefcase fa-sm"></i>' !!}
            @elseif($answer->content->author->expert)
              {!! '&nbsp;<i class="fas fa-medal fa-sm"></i>' !!}
            @endif
          </a>
        </small>
      </p>
      </blockquote>
    </footer>
</div>