<?php require 'templates/head.html'; ?>
<?php require 'templates/header.html'; ?>

<body>
  <div id="dummy">
    Pages
    <ul id="list">
      <li><a href="home.html">Home</a> Page</li>
      <li><a href="profile.html">Profile</a> Page</li>
      <li><a href="question.html">Question</a> Page</li>
    </ul>
  </div>

  <main>
    <article class="mural">
      <section class="question">
        <h3>Is Java a good programming language?</h3>
        <p>I was wondering if I should study it</p>
      </section>
      <section class="question"></section>
    </article>

    <aside class="sidebar">
      <section class="themes">
        <ul>
          <li>OS</li>
          <li>Mobile</li>
          <li>Consoles</li>
        </ul>
      </section>
    </aside>
  </main>

  <div id="test">
    <button type="button" class="btn btn-primary">Primary</button>
  </div>
</body>

<?php require 'templates/footer.html'; ?>

</html>