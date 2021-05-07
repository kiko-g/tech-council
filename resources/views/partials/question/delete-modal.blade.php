<!-- Modal -->
<div class="modal fade" id="delete-answer-modal-{{ $answer->content_id }}" tabindex="-1"
    aria-labelledby="delete-answer-modal-{{ $answer->content_id }}-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="delete-answer-modal-{{ $answer->content_id }}-label">Delete
                    answer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                Deleting answer to question: {{ $answer->question->title }}
                <div class="alert alert-warning mt-2" role="alert">
                    Warning! This action is not reversible. The answer and associated comments will be permanently
                    deleted.
                </div>
            </div>
            <div class="modal-footer">
                <form class="answer-delete" id="answer-delete-{{ $answer->content_id }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-success delete-modal" id="delete-answer-{{ $answer->content_id }}"
                        data-bs-dismiss="modal" type="submit">
                        Delete
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
