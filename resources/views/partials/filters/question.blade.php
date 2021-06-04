<nav id="question-filters" class="navbar-dark bg-petrol mb-3 rounded p-2">
  <div class="btn-toolbar justify-content-between px-1">
    <div class="btn-group btn-group-vertical-when-responsive" role="group" aria-label="Basic radio toggle button group">
      <input type="radio" class="btn-check" name="{{$filter_prefix}}_btnradio" id="filterRadio1_best" autocomplete="off" checked>
      <label class="btn blue-alt rounded-when-responsive mb-responsive text-start-responsive" for="filterRadio1_best">
        <i class="fas fa-at fa-xs text-red-400"></i>&nbsp;Best
      </label>

      <input type="radio" class="btn-check" name="{{$filter_prefix}}_btnradio" id="filterRadio2_new" autocomplete="off">
      <label class="btn blue-alt rounded-when-responsive mb-responsive text-start-responsive" for="filterRadio2_new">
        <i class="fas fa-concierge-bell fa-xs text-teal-300"></i>&nbsp;New
      </label>

      <input type="radio" class="btn-check" name="{{$filter_prefix}}_btnradio" id="filterRadio3_trending" autocomplete="off">
      <label class="btn blue-alt rounded-when-responsive mb-responsive text-start-responsive" for="filterRadio3_trending">
        <i class="fas fa-fire fa-xs text-orange-300"></i>&nbsp;Trending
      </label>

      <input type="radio" class="btn-check" name="{{$filter_prefix}}_btnradio" id="filterRadio4_interactions" autocomplete="off">
      <label class="btn blue-alt rounded-when-responsive text-start-responsive" for="filterRadio4_interactions">
        <i class="fas fa-comments fa-xs text-gray"></i>&nbsp;Interactions
        {{--  <span class="badge align-middle">57</span>  --}}
      </label>
    </div>

    <div class="btn-group btn-group-vertical-when-responsive" role="group" aria-label="Basic radio toggle button group">
      <form action="
          @auth 
            {{ route('create/question') }}
          @endauth
          @guest
            {{ route('login') }}
          @endguest
        ">
        <input type="submit" class="btn-check" id="ask-question-questions">
        <label class="btn blue" for="ask-question-questions">
          Ask Question&nbsp;<i class="fas fa-plus-square fa-xs"></i>
        </label>
      </form>
    </div>
  </div>
</nav>
