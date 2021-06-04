@include('partials.head', ['js' => $js ?? []])

<body>
  @include('partials.header', ['user' => Auth::user(), 'search_string' => $search_string ?? ''])
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
