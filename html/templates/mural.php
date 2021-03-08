<?php include_once 'question-card.php' ?>

<article class="col-lg-9">
  <?php require 'filters.php'; ?>

  <div>
    <?php
    for ($i = 0; $i < 5; $i++) {
      buildQuestion(null);
    }
    ?>
  </div>

  <nav>
    <ul class="pagination justify-content-center">
      <li class="page-item"><a class="page-link" href="#">Previous</a></li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
  </nav>
</article>