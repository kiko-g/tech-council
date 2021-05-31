@php
if ($type == 'question') {
    $before_title = 'Deleting question with title:';
    $warning_message = 'Warning! This action is not reversible. The question and associated answers and comments will be permanently deleted.';
} else {
    $before_title = 'Deleting answer to question:';
    $warning_message = 'Warning! This action is not reversible. The answer and associated comments will be permanently deleted.';
}
@endphp

<div class="modal fade" id="delete-{{ $type }}-modal-{{ $content_id }}" tabindex="-1"
    aria-labelledby="delete-{{ $type }}-modal-{{ $content_id }}-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="delete-{{ $type }}-modal-{{ $content_id }}-label">Delete {{ $type }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                {{ $before_title }} {{ $title }}
                <div class="alert alert-warning mt-2" role="alert">
                    {{ $warning_message }}
                </div>
            </div>
            <div class="modal-footer">
                <form class="{{ $type }}-delete" id="{{ $type }}-delete-{{ $content_id }}" method="post"
					action="{{ url('/' . $type . '/' . $content_id . '/delete') }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-success @if (!$redirect) delete-modal @endif" 
						id="delete-{{ $type }}-{{ $content_id }}"
                        @if (!$redirect) data-bs-dismiss="modal" @endif type="submit">
                        Delete
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <form class="{{ $type }}-delete" id="{{ $type }}-delete-{{ $content_id }}" method="post"
          action="{{ url('/' . $type . '/' . $content_id . '/delete') }}">
          @method('DELETE')
          @csrf
          <button class="btn btn-success teal @if (!$redirect) delete-modal @endif" id="delete-{{ $type }}-{{ $content_id }}" @if (!$redirect) data-bs-dismiss="modal" @endif type="submit">
            Delete
          </button>
        </form>
        <button type="button" class="btn btn-danger wine" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
