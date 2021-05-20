<div class="card mb-3">
    <div class="card-header text-white bg-petrol font-source-sans-pro bg-animated"> Followed Tags </div>
    <div class="card-body lh-lg" id="followed-tags">
        @foreach ($followedTags as $followedTag)
            <div class="btn-group" id="followed-tag-{{ $followedTag->tag->id }}">
                <a href="{{ route('tag', ['id' => $followedTag->tag->id]) }}"
                   class="btn blue-alt border-0 my-btn-pad2">
                   {{ $followedTag->tag->name }}
                </a>
            </div>
        @endforeach
    </div>
</div>
