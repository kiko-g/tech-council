<article class="col-lg-9 ">
  <?php require 'filters.php'; ?>

  <div>
    <?php
    for ($i = 0; $i < 5; $i++) {
      require 'question-card.php';
    }
    ?>
  </div>
</article>