<nav id="tag-filters" class="navbar-dark bg-petrol mb-3 rounded p-2">
  <div class="btn-toolbar justify-content-between px-1">
    <div class="btn-group btn-group-vertical-when-responsive" role="group" aria-label="Basic radio toggle button group">
      <input type="radio" class="btn-check" name="{{$filter_prefix}}_btnradio" id="tagFilterRadio5_follows" checked>
      <label class="btn blue-alt rounded-when-responsive mb-responsive text-start-responsive" for="tagFilterRadio5_follows">
        <i class="fas fa-volume-up fa-xs text-red-400"></i>&nbsp;Followers
      </label>

      <input type="radio" class="btn-check" name="{{$filter_prefix}}_btnradio" id="tagFilterRadio6_questions">
      <label class="btn blue-alt rounded-when-responsive mb-responsive text-start-responsive" for="tagFilterRadio6_questions">
        <i class="fas fa-question-circle fa-xs text-teal-300"></i>&nbsp;Questions
      </label>

      <input type="radio" class="btn-check" name="{{$filter_prefix}}_btnradio" id="tagFilterRadio7_alphabetical">
      <label class="btn blue-alt rounded-when-responsive mb-responsive text-start-responsive" for="tagFilterRadio7_alphabetical">
        <i class="fas fa-font fa-xs text-orange-300"></i>&nbsp;Alphabetical
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
        <input type="submit" class="btn-check" id="ask-question-tags">
        <label class="btn blue" for="ask-question-tags">
          Ask Question&nbsp;<i class="fas fa-plus-square fa-xs"></i>
        </label>
      </form>
    </div>
  </div>
</nav>
