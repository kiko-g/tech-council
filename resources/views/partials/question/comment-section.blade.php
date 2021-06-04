<article id="comment-section-{{ $id }}" class="row mt-4">
  @php
    $comment_limit = 2;
    $comment_count = 0;
  @endphp

  @foreach ($comments as $comment)
    @php $comment_count++; @endphp
    <div
      class="comment-box<?php if ($comment_count > $comment_limit) { ?> collapse hidden{{ $id }} <?php } ?>">
      <div class="comment d-flex justify-content-between shadow-sm border border-2 mb-1 px-2 bg-light rounded">
        <p class="mb-0 w-75">{!! $comment->content->main !!}</p>
        <blockquote class="blockquote mb-0">
          <p class="card-text mb-0">
            <small class="text-muted">{{ $comment->content->creation_date }}&nbsp;&#8226;
              <a class="signature" href="{{ url('user/' . $comment->content->author->id) }}">{{ $comment->content->author->name }}</a>
              @if ($comment->content->author->moderator)
                @include('partials.icons.moderator', ['width' => 15, 'height' => 15, 'title' => 'Moderator'])
              @elseif($comment->content->author->expert)
                @include('partials.icons.medal', ['width' => 15, 'height' => 15, 'title' => 'Expert User'])
              @endif
              &#8226;
            </small>
            <a class="clear-text">
              <i class="fas fa-flag fa-sm text-red-400 hover " data-bs-toggle="modal" id="report-button-{{ $comment->content_id }}"
                data-bs-target="#report-modal-{{ $comment->content_id }}">
              </i>
              @include('partials.report-modal', [
                "type" => "comment",
                "content_id" => $comment->content_id,
              ])
            </a>
          </p>
        </blockquote>

      </div>
    </div>
  @endforeach


  @if ($comment_count > $comment_limit)
    <a class="show-more text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden{{ $id }}"
      aria-expanded="false">Show {{ $comment_count - $comment_limit }} more comments
    </a>
    <a class="show-less text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden{{ $id }}"
      aria-expanded="false">Hide {{ $comment_count - $comment_limit }} last comments
    </a>
  @endif

  <form class="collapse" id="collapse{{ $id }}">
    <textarea class="form-control shadow-sm border border-2 bg-light" rows="2"
      placeholder="Type your comment"></textarea>
    <div class="float-end">
      <a class="btn btn-success teal text-white mt-2 me-2" role="button" aria-expanded="false">
        Submit
      </a>
      <a class="btn btn-danger wine text-white mt-2" data-bs-toggle="collapse" href="#collapse{{ $id }}"
        role="button" aria-expanded="false" aria-controls="collapse{{ $id }}">
        Close
      </a>
    </div>
  </form>

  <div class="float-end mt-2">
    <a class="float-end btn blue-alt extra text-white add-comment px-2 py-1" data-bs-toggle="collapse"
      href="#collapse{{ $id }}" role="button" aria-expanded="false"
      aria-controls="collapse{{ $id }}">
      Add comment
    </a>
  </div>

</article>
