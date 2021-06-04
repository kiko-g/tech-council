@auth @include('partials.aside.trends', ['trendingTags' => $user->followedTags]) @endauth
@auth @include('partials.aside.tag-follows', ['followedTags' => $user->followedTags]) @endauth
@include('partials.aside.support')
@include('partials.aside.info')