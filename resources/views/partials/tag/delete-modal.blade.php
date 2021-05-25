<div class="modal fade" id="delete-tag-modal-{{ $tag->id }}" tabindex="-1"
    aria-labelledby="delete-tag-modal-{{ $tag->id }}-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="delete-tag-modal-{{ $tag->id }}-label">Delete tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                Tag name: {{ $tag->name }}
                <div class="alert alert-warning mt-2" role="alert">
                    Warning! This action is not reversible. The tag will be permanently deleted and removed from related content.
                </div>
            </div>
            <div class="modal-footer">
                <form class="tag-delete" id="tag-delete-{{ $tag->id }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-success delete-tag-modal" 
						id="delete-tag-{{ $tag->id }}"
                        data-bs-dismiss="modal" type="submit" data-tag-id="{{ $tag->id }}">
                        Delete
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>