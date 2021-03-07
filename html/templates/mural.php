<?php include_once 'question-card.php' ?>

<article class="col-lg-9 ">
  <?php require 'filters.php'; ?>

  <div>
    <?php
    for ($i = 0; $i < 5; $i++) {
      buildQuestion(null);
    }
    ?>
  </div>
</article>