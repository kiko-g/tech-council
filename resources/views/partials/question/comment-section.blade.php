<article id="comment-section-{{ $id }}" class="row mt-4">
  @php
    $comment_limit = 2;
    $comment_count = 0;
  @endphp

  <div id="comments-{{ $id }}">
    @foreach ($comments as $comment)
      @php $comment_count++; @endphp
      @include('partials.question.comment', ['comment' => $comment])
    @endforeach
  </div>


  @if ($comment_count > $comment_limit)
    <a class="show-more text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden{{ $id }}"
      aria-expanded="false">Show {{ $comment_count - $comment_limit }} more comments
    </a>
    <a class="show-less text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden{{ $id }}"
      aria-expanded="false">Hide {{ $comment_count - $comment_limit }} last comments
    </a>
  @endif

  <form class="collapse" id="collapse{{ $id }}">
    <textarea id="comment-main-{{ $id }}" class="form-control shadow-sm border border-2 bg-light" rows="2"
      placeholder="Type your comment"></textarea>
    <div class="float-end">
      <a id="submit-comment-{{ $id }}" class="submit-comment btn btn-success teal text-white mt-2 me-2"
        data-bs-toggle="collapse" href="#collapse{{ $id }}" role="button" aria-expanded="false"
        aria-controls="collapse{{ $id }}" data-parent-id="{{ $id }}"
        data-parent-type="{{ $type }}">
        Submit
      </a>
      <a class="btn btn-danger wine text-white mt-2" data-bs-toggle="collapse" href="#collapse{{ $id }}"
        role="button" aria-expanded="false" aria-controls="collapse{{ $id }}">
        Close
      </a>
    </div>
  </form>

  <div class="float-end mt-2">
    <a class="add-comment float-end btn blue-alt extra text-white add-comment px-2 py-1" data-bs-toggle="collapse"
      href="#collapse{{ $id }}" role="button" aria-expanded="false"
      aria-controls="collapse{{ $id }}" data-parent-id="{{ $id }}">
      Add comment
    </a>
  </div>

</article>
