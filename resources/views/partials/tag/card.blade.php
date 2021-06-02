@php
  if(isset($user)) $follows = $user->followsTag($tag->id);
  else $follows = null;

  if ($follows) {
    $follow_class = 'active-follow';
    $follow_text = 'Following';
    $follow_icon = 'fa';
  }
  else {
    $follow_class = 'follow';
    $follow_text = 'Follow';
    $follow_icon = 'far';
  }
@endphp

<div class="card mb-4 p-2-0 border-0 rounded">
  <div class="card-header bg-animated longer text-white font-source-sans-pro rounded-top">
    # {{ $tag->name }}
  </div>
  <div class="card-body">
    <p class="mb-3">
      {{ $tag->description }}
    </p>
    <div class="row row-cols-3 mb-1">
      <div id="interact" class="col-lg flex-wrap">
        <div class="btn-group mt-1 rounded">
          <a class="upvote-button my-btn-pad2 btn btn-outline-success {{ $follow_class }}" id="follow-{{ $tag->id }}"
            @auth onclick="toggleFollow(this)" @endauth
            @guest href={{ route('login') }} @endguest>
            <i class="{{ $follow_icon }} fa-star"></i>&nbsp;{{ $follow_text }}
          </a>
        </div>
        <div class="btn-group mt-1 rounded">
          <a class="upvote-button btn blue my-btn-pad2" id="upvote-button" href="#">
            <i class="fas fa-share-alt"></i>&nbsp;Share
          </a>
        </div>
      </div>

      <div id="facts" class="col-lg-auto">
        <div class="btn-group mt-1 rounded">
          <span class="upvote-button btn blue-alt static my-btn-pad2 nohover" id="upvote-button">
            <i class="fas fa-fire"></i>&nbsp;21k followers {{--TODO: check followers --}}
          </span>
        </div>
        <div class="btn-group mt-1 rounded">
          <span class="upvote-button btn blue-alt static my-btn-pad2 nohover" id="upvote-button">
            <i class="fas fa-question"></i>&nbsp;168k questions {{--TODO: check questions --}}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
