<section id="search-tag-results">
  @if (count($tags) === 0)
    @include('partials.search.noresults')
  @else
    @foreach ($tags as $tag)
      @include('partials.tag.card', ['tag' => $tag, 'user' => $user])
    @endforeach
    @include('partials.pagination', ['type' => "search-tag-"])
  @endif
</section>
