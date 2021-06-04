<section id="search-tag-results">
  @if (count($tags) === 0)
    <div class="card">
      <div class="card-body">
          No results
      </div>
    </div>
  @else
    @foreach ($tags as $tag)
      @include('partials.tag.card', ['tag' => $tag, 'user' => $user])
    @endforeach
    @include('partials.pagination', ['type' => "search-tag-"]) 
  @endif
</section>
