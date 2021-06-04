<section id="search-tag-results">
  @if (count($tags) === 0)
    @include('partials.search.noresults')
  @else
    @include('partials.tag.table', ['displayed_tags' => $tags])
    @include('partials.pagination', ['type' => "search-tag-"])
  @endif
</section>
