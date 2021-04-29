@include('partials.head')

<body>
  @include('partials.header', ['user' => $user])
  <div class="d-flex entry-form flex-column justify-content-center border-top-bg">
    @yield('content')
  </div>
  @include('partials.footer')
</body>

</html>
