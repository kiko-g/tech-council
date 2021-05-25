<button type="button" class="tag-editing collapse show tag-{{ $tag->id }}-edit btn btn-sm teal" data-bs-toggle="collapse"
    data-bs-target=".tag-{{ $tag->id }}-edit" data-tag-id="{{ $tag->id }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-pencil-fill"
        viewBox="0 0 16 16">
        <path
            d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
    </svg>
</button>
<button type="button" class="collapse show tag-{{ $tag->id }}-edit btn btn-sm red" data-bs-toggle="modal"
    data-bs-target="#delete-tag-modal-{{ $tag->id }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-trash-fill"
        viewBox="0 0 16 16">
        <path
            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
    </svg>
</button>

<button type="button" id="confirm-tag-{{ $tag->id }}-edit" class="btn btn-sm teal collapse tag-{{ $tag->id }}-edit"
    data-bs-toggle="collapse" data-bs-target=".tag-{{ $tag->id }}-edit" aria-expanded="false" data-tag-id="{{ $tag->id }}">
    <i class="fas fa-check"></i>
</button>
<button type="button" id="cancel-tag-{{ $tag->id }}-edit" class="btn btn-sm red collapse tag-{{ $tag->id }}-edit"
    data-bs-toggle="collapse" data-bs-target=".tag-{{ $tag->id }}-edit" aria-expanded="false" data-tag-id="{{ $tag->id }}">
    <i class="fas fa-close"></i>
</button>

@include('partials.tag.delete-modal', ["tag" => $tag])
