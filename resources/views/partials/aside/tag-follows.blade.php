<div class="card mb-3">
    <div class="card-header text-white bg-petrol font-source-sans-pro bg-animated"> Followed Tags </div>
    <div class="card-body lh-lg" id="followed-tags">
        @foreach ($followTags as $followTag)
            <div class="btn-group" id="followed-tag-{{ $followTag->tag->id }}">
                <a href="{{ route('tag', ['id' => $followTag->tag->id]) }}"
                    class="btn blue-alt border-0 my-btn-pad2">{{ $followTag->tag->name }}</a>
            </div>
        @endforeach
    </div>
</div>
