@include('partials.head')

<body>
  @include('partials.header', ['user' => Auth::user()])
  <div class="d-flex container-fluid entry-form flex-column justify-content-center border-top-bg">
    @yield('content')
  </div>
  @include('partials.footer')
</body>

</html>
