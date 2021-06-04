<div
    class="comment-box @if(isset($comment_count) && isset($comment_limit) && $comment_count > $comment_limit)collapse hidden{{ $id }}@endif">
    <div class="comment d-flex justify-content-between shadow-sm border border-2 mb-1 px-2 bg-light rounded">
        <p class="mb-0 w-75">{!! $comment->content->main !!}</p>
        <blockquote class="blockquote mb-0">
            <p class="card-text mb-0">
                <small class="text-muted">{{ $comment->content->creation_date }}&nbsp;&#8226;
                    <a class="signature"
                        href="{{ url('user/' . $comment->content->author->id) }}">{{ $comment->content->author->name }}</a>
                    @if ($comment->content->author->moderator)
                        @include('partials.icons.moderator', ['width' => 15, 'height' => 15, 'title' => 'Moderator'])
                    @elseif($comment->content->author->expert)
                        @include('partials.icons.medal', ['width' => 15, 'height' => 15, 'title' => 'Expert User'])
                    @endif
                    &#8226;
                    <a class="text-red-400 hover">
                        <i class="fas fa-flag fa-sm" data-bs-toggle="modal"
                            id="report-button-{{ $comment->content_id }}"
                            data-bs-target="#report-modal-{{ $comment->content_id }}">
                        </i>
                        @include('partials.report-modal', [
                        "type" => "comment",
                        "content_id" => $comment->content_id,
                        ])
                    </a>
                </small>
            </p>
        </blockquote>

    </div>
</div>
