<?php include_once '../templates/tag-card.php' ?>
<?php include_once '../templates/question-card.php' ?>
<?php require '../templates/head.php' ?>

<body>
  <?php require '../templates/header.php' ?>
  <main class="container">
    <div class="row">
      <article class="col-lg-9">
        <?php
        buildTag(null);
        require '../templates/division.php';
        require '../templates/filters.php';
        buildQuestion(null);
        buildQuestion(null);
        ?>
      </article>
      <?php require '../templates/aside.php' ?>
    </div>
  </main>
  <?php require '../templates/footer.php' ?>
</body>

</html>