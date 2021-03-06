<article class="col-lg-9 ">
  <?php require 'filter-bar.php'; ?>

  <div>
    <?php
    for ($i = 0; $i < 5; $i++) {
      require 'question-card.php';
    }
    ?>
  </div>
</article>