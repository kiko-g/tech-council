<section id="search-tag-results">
    @if (count($tags) === 0)
        <div class="card">
        <div class="card-body">
            No results
        </div>
        </div>
    @else
        @include('partials.tag.table', ['displayed_tags' => $tags])
        @include('partials.pagination', ['type' => "search-tag-"]) 
    @endif
</section>
