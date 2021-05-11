@include('partials.aside.trends')
@auth @include('partials.aside.tag-follows', ['followTags' => $user->followTags]) @endauth
@include('partials.aside.info')
