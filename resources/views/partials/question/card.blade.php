@php
if (isset($user)) {
  $hasSaved = $user->hasQuestionSaved($question->content_id, $user->id);
  $hasResported = $question->isReportedByUser();
} else {
  $hasSaved = null;
  $hasResported = null;
}

if ($hasSaved) {
  $save_class = 'active-bookmark';
  $save_text = 'Saved';
  $save_icon = 'fa';
} else {
  $save_class = 'bookmark';
  $save_text = 'Save';
  $save_icon = 'far';
}

if ($hasResported) {
  $report_class = 'active-report';
  $report_text = 'Reported';
  $report_icon = 'fa';  
  $report_availability = 'disabled';
} else {
  $report_class = 'report';
  $report_text = 'Report';
  $report_icon = 'far';
  $report_availability = '';
}
@endphp

<div class="card mb-4 p-2-0 border-0 rounded" id="{{ 'question-' . $question->content_id }}">
  <div class="card-header bg-petrol text-white font-source-sans-pro rounded-top">
    <div class="row">
      <div class="col-auto me-auto">
        <a class="a header" href="{{ url('question/' . $question->content_id) }}">
            {{ $question->title }}<i class="fas fa-link fa-xs text-blue-200 mt-1dot5 ms-2"></i>&nbsp;&nbsp;&nbsp;
        </a>
        @auth
          @if (Auth::user()->id == $question->content->author_id)
            &nbsp;<span class="badge my-post-signature">My post</span>
          @endif
          @if ($question->content->edited)
            &nbsp;<span class="badge edit-signature">Edited</span>
          @endif
        @endauth
      </div>
      @auth
        @if (Auth::user()->id == $question->content->author_id || Auth::user()->moderator)
          <div class="col-auto btn-group">
            @if (Auth::user()->id == $question->content->author_id && $include_comments)
              <a type="button" class="btn p-0 collapse show edit-question-button" href={{ url("edit/question/" . $question->content_id) }}>
                <i class="fas fa-pencil-alt text-yellow-400 hover mt-1 ms-2"></i>
              </a>
            @elseif(Auth::user()->moderator && $include_comments)
              <button type="button" class="btn p-0 collapse show moderator-edit edit-question-button"
                data-bs-toggle="collapse" data-bs-target=".moderator-edit" aria-expanded="false">
                <i class="fas fa-pencil-alt text-yellow-400 hover mt-1 ms-2"></i>
              </button>
            @endif

            {{-- Confirm edit buttons --}}
            <button type="button" id="confirm-edit" class="btn p-0 collapse moderator-edit" data-bs-toggle="collapse"
              data-bs-target=".moderator-edit" aria-expanded="false">
              <i class="fas fa-check text-teal-400 hover mt-1 ms-2"></i>
            </button>
            <button type="button" id="cancel-edit" class="btn p-0 collapse moderator-edit" data-bs-toggle="collapse"
              data-bs-target=".moderator-edit" aria-expanded="false">
              <i class="fas fa-close text-red-400 hover mt-1 ms-2"></i>
            </button>

            <button type="button" class="btn p-0 delete-question-modal-trigger collapse show moderator-edit"
              data-bs-toggle="modal" data-bs-target="#delete-question-modal-{{ $question->content_id }}">
              <i class="far fa-trash-alt text-red-400 hover mt-1 ms-2"></i>
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
    <article class="row row-cols-2 mb-1 pe-1">
      <div class="col-auto flex-wrap">
        <div id="votes-{{ $question->content_id }}" class="votes btn-group-vertical mt-1 flex-wrap">
          @php
            $upClass = 'teal';
            $downClass = 'pink';
            if ($voteValue === 1) {
                $upClass = 'active-teal';
            }
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
            {{ $question->votes_difference }}
          </a>
          <a id="downvote-button-{{ $question->content_id }}"
            class="downvote-button-question my-btn-pad down btn btn-outline-danger {{ $downClass }}"
            data-content-id="{{ $question->content_id }}">
            <i class="fas fa-chevron-down"></i>
          </a>
        </div>
      </div>

      <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0">
        <div id="{{ 'question-content-' . $question->content_id }}" class="mb-1"> {!! $question->content->main !!} </div>
        <div class="row row-cols-2 mb-1">
          <div id="interact" class="col-md flex-wrap">
            <div class="btn-group mt-1 rounded">
              <a class="star-button my-btn-pad2 btn btn-outline-success {{ $save_class }}"
                id="save-{{ $question->content_id }}" 
                @guest href={{ route('login') }} @endguest
                @auth onclick="toggleSave(this)" @endauth>
                <i class="{{ $save_icon }} fa-bookmark"></i>&nbsp;{{ $save_text }}
              </a>
            </div>
            <div class="btn-group mt-1 rounded">
              <a class="report-button my-btn-pad2 btn btn-outline-success {{ $report_class }} {{ $report_availability }}" 
                id="report-button-{{ $question->content_id }}"
                @guest href={{ route('login') }} @endguest 
                @auth data-bs-toggle="modal" data-bs-target="#report-modal-{{ $question->content_id }}" @endauth>
                <i class="{{ $report_icon }} fa-flag"></i>&nbsp;{{ $report_text }}
              </a>
            </div>
            @if (!$include_comments)
            <div class="btn-group mt-1 rounded">
              <a class="comment-number-button btn teal my-btn-pad2"
                id="comment-number-button-{{ $question->content_id }}"
                href="{{ url('question/' . $question->content_id . '#answers') }}">
                <i class="far fa-comment-dots"></i>&nbsp;{{ $question->countInteractions() }}
              </a>
            </div>
            @endif
            <div class="btn-group mt-1 rounded">
              <a class="share-button btn blue my-btn-pad2" id="share-button-{{ $question->content_id }}" href="#">
                <i class="fas fa-share-alt"></i>
              </a>
            </div>
            @include('partials.report-modal', [
              "type" => "question",
              "content_id" => $question->content_id,
            ])
          </div>

          <div id="tags" class="col-md-auto flex-wrap">
            <div class="collapse show moderator-edit" id="tag-original-view">
              @foreach ($question->tags as $tag)
                <div class="btn-group mt-1" id="question-tag-{{ $tag->id }}">
                  <a class="btn blue-alt border-0 my-btn-pad2"
                    href="{{ route('tag', ['id' => $tag->id]) }}">{{ $tag->name }}</a>
                </div>
              @endforeach
            </div>
            <div class="collapse moderator-edit" id="tag-moderator-view">
              @foreach ($question->tags as $tag)
                <div class="tag-edit btn-group mt-1" id="question-tag-edit-{{ $tag->id }}"
                  data-tag-id="{{ $tag->id }}" data-question-id="{{ $question->content_id }}">
                  <a class="btn blue-alt border-0 my-btn-pad2"><i
                      class="fas fa-minus-square"></i>&nbsp;{{ $tag->name }}</a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        @if ($include_comments)
          @include('partials.question.comment-section', ['comments' => $question->comments, 'id' =>
          $question->content_id])
        @endif
      </div>
    </article>
  </div>

  <footer class="card-footer text-muted text-end p-0">
    <blockquote class="blockquote mb-0">
      <p class="card-text px-1 h6">
        <small class="text-muted">asked {{ $question->content->creation_date }}</small>
        <small>
          <a class="signature"
            href="{{ url('user/' . $question->content->author->id) }}">{{ $question->content->author->name }}
            @if ($question->content->author->moderator)
              @include('partials.icons.moderator', ['width' => 20, 'height' => 20, 'title' => 'Moderator'])
            @elseif($question->content->author->expert)
              @include('partials.icons.medal', ['width' => 20, 'height' => 20, 'title' => 'Expert User'])
            @endif
          </a>
        </small>
      </p>
    </blockquote>
  </footer>
</div>
{{-- <div class="collapse mt-2" id="collapseQuestionText2">
  Section inside <code>collapse</code>.
</div> 
<button class="btn btn-outline-info dark border-0 py-0 px-1" type="button" onclick="toogleText(this)"
  data-bs-toggle="collapse" data-bs-target="#collapseQuestionText2" aria-expanded="false"
  aria-controls="collapseQuestionText2">
  <i class="fas fa-ellipsis-h"></i>
</button>  --}}
