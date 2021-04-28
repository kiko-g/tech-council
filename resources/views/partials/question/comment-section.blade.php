<?php $comment_limit = 3; ?>
<div class="mb-3">
  <?php
  $comment_count = 0;
  foreach ($comments as $comment) {
    $comment_count++; ?>
    <div class="<?php if ($comment_count > $comment_limit) { ?> collapse hidden<?= $id ?> <?php } ?>">
      <div class="comment d-flex justify-content-between shadow-sm border border-2 mb-1 px-2 bg-light rounded">
        <p class="mb-0"><?= $comment->content->main ?></p>
        <blockquote class="blockquote mb-0">
          <p class="card-text mb-0"><small class="text-muted"><?= $comment->content->creation_date ?>&nbsp;<a class="signature" href="#"><?= $comment->content->author_id ?></a></small></p>
        </blockquote>
      </div>
    </div>
  <?php
  }

  if ($comment_count > $comment_limit) { ?>
    <a class="show-more text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden<?= $id ?>" aria-expanded="false">Show <?= $comment_count - $comment_limit ?> more comments</a>
    <a class="show-less text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden<?= $id ?>" aria-expanded="false">Hide <?= $comment_count - $comment_limit ?> last comments</a>
  <?php 
  } ?>

  <form class="collapse" id="collapse<?= $id ?>">
   <textarea class="form-control shadow-sm border border-2 bg-light" rows="2" placeholder="Type your comment"></textarea>
   <div class="float-end">
    <a class="btn btn-success teal text-white mt-2 me-2" role="button" aria-expanded="false">
     Submit
    </a>
    <a class="btn btn-danger wine text-white mt-2" data-bs-toggle="collapse" href="#collapse<?= $id ?>" role="button" aria-expanded="false" aria-controls="collapse<?= $id ?>">
     Close
    </a>
   </div>
  </form>
  <a class="float-end btn blue-alt extra text-white add-comment mt-3" data-bs-toggle="collapse" href="#collapse<?= $id ?>" role="button" aria-expanded="false" aria-controls="collapse<?= $id ?>">
   Add comment
  </a>
 </div>
