<div class="card mb-3">
  <div class="card-header text-white bg-petrol font-source-sans-pro bg-animated rounded-top"> Trending Tags </div>
  <div class="card-body lh-lg" id="trending-tags">
    @foreach($trendingTags as $trendingTag)
      <div class="btn-group" id="trending-tag-{{ $trendingTag->tag->id }}">
        <a href="{{ route('tag', ['id' => $trendingTag->tag->id]) }}" class="btn blue-alt border-0 my-btn-pad2">
          {{ $trendingTag->tag->name }}
        </a>
      </div>
    @endforeach
  </div>
</div>

{{-- 
<div class="card-body">
  <div class="list-group" id="list-tab" role="tablist">
    <a class="list-group-item list-group-item-action d-flex justify-content-between active" data-bs-toggle="list" href="{{ url('/') }}" role="tab" aria-controls="home"> All tags </a>
    <a href="/" class="list-group-item list-group-item-action d-flex justify-content-between" data-bs-toggle="list" role="tab" aria-controls="home">
      Ubuntu <span class="badge">217</span>
    </a>
  </div>
</div> 
--}}