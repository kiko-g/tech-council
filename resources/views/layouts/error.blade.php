@include('partials.head')

<body>
  @include('partials.header', ['user' => $user])
  <main class="container d-flex justify-content-center my-4">
    @yield('body')
  </main>
</body>

</html>
