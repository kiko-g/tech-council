<?php include_once 'question-card.php' ?>
<?php include_once 'question-card-alt.php' ?>

<article class="col-lg-9">
  <?php require 'filters.php'; ?>

  <div>
    <?php
    for ($i = 0; $i < 2; $i++) {
      buildQuestion(null);
      buildQuestion2(null);
    }
    ?>
  </div>

  <nav>
    <ul class="pagination justify-content-center">
      <li class="page-item">
        <a class="page-link blue" href="#" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </a>
      </li>

      <li class="page-item"><a class="page-link blue" href="#">1</a></li>
      <li class="page-item"><a class="page-link blue active" href="#">2</a></li>
      <li class="page-item"><a class="page-link blue" href="#">3</a></li>

      <li class="page-item">
        <a class="page-link blue" href="#" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
      </li>
    </ul>
  </nav>
</article>