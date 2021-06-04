@auth
@if (Auth::user()->id == $answer->content->author_id)
<form class="collapse answer-collapse-{{ $answer->content_id }} container ps-0 answer-edit-form" id="answer-edit-form-{{ $answer->content_id }}" method="post">
    @method('PUT')
    @csrf
    {{--edit text area --}}
    <div id="{{ 'answer-content-' . $answer->content_id }}" class="mb-1">
        <textarea id="answer-submit-input-{{ $answer->content_id }}" name="main" class="form-control shadow-sm border border-2 bg-light" rows="5" placeholder="Type your answer">
            {!! $answer->content->main !!}
        </textarea>
    </div>
</form>
@endif
@endauth
