<section id="search-tag-results">
  @foreach ($tags as $tag)
    @include('partials.tag.card', ['tag' => $tag, 'user' => $user])
  @endforeach
  @include('partials.pagination', ['type' => "search-tag-"])
</section>
