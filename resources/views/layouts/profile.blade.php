@include('partials.head', ['js' => $js ?? []])

<body>
  @include('partials.header', ['user' => Auth::user()])
  <main class="container">
    <div class="row">
      <article class="col-lg-9 order-last order-lg-first">
        @yield('content')
      </article>
      <aside class="col-lg-3 mb-5">
        @yield('aside')
      </aside>
    </div>
  </main>
  @include('partials.footer')
</body>

</html>
