<div class="card mb-4 border-0 p-0 rounded bg-<?= $answer->is_best_answer ? 'teal-600' : 'background-color' ?>">
  <div class="card m-1">
    <div class="card-body">
      <p class="mb-3">
      <?= $answer->content->main ?>
      </p>

      <div class="row row-cols-3 mb-4" data-content-id="{{ $answer->content_id }}">
        <div class="col-lg flex-wrap">
          <div id="votes-{{ $answer->content_id }}" class="votes btn-group-special btn-group-vertical-when-responsive mt-1 flex-wrap">
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
      </div>

      @include('partials.question.comment-section', ['comments' => $answer->comments, 'id' => $answer->content_id])
    </div>

    <div class="card-footer text-muted text-end p-0">
      <blockquote class="blockquote mb-0">
      <p class="card-text px-1"><small class="text-muted">asked Aug 14 2020 at 15:31&nbsp;<a class="signature" href="#">user</a></small></p>
      </blockquote>
    </div>
  </div>
</div>
