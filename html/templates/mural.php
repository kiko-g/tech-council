<article class="mural rounded">
  <?php require 'filters.php'; ?>

  <div>
    <?php
    for ($i = 0; $i < 5; $i++) {
      require 'question-card.php';
    }

    // for ($i = 0; $i < 5; $i++) {
    //   require 'question.php';
    // }
    ?>
  </div>
</article>