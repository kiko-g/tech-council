@include('partials.aside.trends')
@auth @include('partials.aside.tag-follows', ['followedTags' => $user->followedTags]) @endauth
@include('partials.aside.info')
