@include('partials.tag-card')
@include('partials.question-card')
@include('partials.head')

<body>
    @include('partials.header')
  <main class="container">
    <div class="row">
      <article class="col-lg-9">
        <?php buildTag(null); ?>

        @include('partials.division')
        @include('partials.filters')
        
        <?php
        buildQuestion(null);
        buildQuestion(null);
        ?>
      </article>
      @include('partials.aside')
    </div>
  </main>
  @include('partials.footer')
</body>

</html>