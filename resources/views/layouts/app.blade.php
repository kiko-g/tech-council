@include('partials.head')

<body>
  @include('partials.header')
  @yield('search')
  <main class="container">
    <div class="row">
      <article class="col-lg-9">
        @yield('content')
      </article>
      <aside class="col-lg-3">
        @yield('aside')
      </aside>
    </div>
  </main>
  @include('partials.footer')
</body>

</html>
