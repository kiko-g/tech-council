<?php function buildAnswer($answer, $identifier)
{
?>
<div class="card mb-4 border-0 p-0 rounded bg-<?= $answer['correct'] ? 'teal-600' : 'background-color' ?>">
  <div class="card m-1">
   <div class="card-body">
    <p class="mb-3">
     <?= $answer['content'] ?>
    </p>

    <div class="row row-cols-3 mb-4">
     <div class="col-lg flex-wrap">
      <div id="votes" class="votes btn-group-special btn-group-vertical-when-responsive mt-1 flex-wrap">
       <a id="upvote-button" class="upvote-button my-btn-pad btn btn-outline-success teal" onclick="vote('up', this.parentNode)">
        <i class="fas fa-chevron-up"></i>
       </a>
       <a id="vote-ratio" class="vote-ratio btn my-btn-pad fake disabled"> 12 </a>
       <a id="downvote-button" class="downvote-button my-btn-pad btn btn-outline-danger pink" onclick="vote('down', this.parentNode)">
        <i class="fas fa-chevron-down"></i>
       </a>
      </div>
     </div>
    </div>

    <?php buildCommentSection($answer['comments'], $identifier); ?>
   </div>

   <div class="card-footer text-muted text-end p-0">
    <blockquote class="blockquote mb-0">
     <p class="card-text px-1"><small class="text-muted">asked Aug 14 2020 at 15:31&nbsp;<a class="signature" href="#">user</a></small></p>
    </blockquote>
   </div>
  </div>
 </div>

<?php
} ?>
