<?php function buildQuestion($comments)
{ ?>
  <div class="card mb-4 p-2-0 border-0 rounded">
    <div class="card-header bg-petrol text-white font-source-sans-pro">
      How do I compare strings in Java?
    </div>
    <div class="card-body">
      <article class="row row-cols-2 mb-1">
        <div class="col-sm-auto flex-wrap">
          <div id="votes" class="votes btn-group-vertical mt-1 flex-wrap">
            <a id="upvote-button" class="upvote-button my-btn-pad up btn btn-outline-success teal" onclick="vote('up', this.parentNode)">
              <i class="fas fa-chevron-up"></i>
            </a>
            <a id="vote-ratio" class="vote-ratio btn my-btn-pad fake disabled"> 42 </a>
            <a id="downvote-button" class="downvote-button my-btn-pad down btn btn-outline-danger pink" onclick="vote('down', this.parentNode)">
              <i class="fas fa-chevron-down"></i>
            </a>
          </div>
        </div>

        <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0">
          <div id="question" class="mb-1">
            I've been using the <code>==</code> operator in my program to compare all my strings so far.
            However, I ran into a bug, changed one of them into <code>.equals()</code> instead, and it fixed the bug.

            <button class="btn btn-outline-info dark border-0 py-0 px-1" type="button" onclick="toogleText(this)" data-bs-toggle="collapse" data-bs-target="#collapseQuestionText2" aria-expanded="false" aria-controls="collapseQuestionText2">
              <i class="fas fa-ellipsis-h"></i>
            </button>

            <div class="collapse mt-2" id="collapseQuestionText2">
              Is <code>==</code> bad? When should it and should it not be used? What's the difference?
              Is <code>==</code> bad? When should it and should it not be used? What's the difference?
              Is <code>==</code> bad? When should it and should it not be used? What's the difference?
              Is <code>==</code> bad? When should it and should it not be used? What's the difference?
            </div>
          </div>

          <div class="row flex-row-reverse mb-3">
            <div id="tags" class="col-lg-auto flex-wrap">
              <div class="btn-group mt-1">
                <a href="#" class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">java</a>
              </div>
              <div class="btn-group mt-1">
                <a href="#" class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">node</a>
              </div>
              <div class="btn-group mt-1">
                <a href="#" class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">msi</a>
              </div>
              <div class="btn-group mt-1">
                <a href="#" class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">nvidia</a>
              </div>
            </div>
          </div>
      </article>

      <?php
      if ($comments != null) buildCommentSection($comments, 1);
      ?>
      <div class="row row-cols-3">
        <div id="interact" class="col-lg-auto" style="padding-left: 0;">
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button my-btn-pad2 btn btn-outline-success bookmark" id="upvote-button-<ID>" onclick="toggleStar(this)" href="#">
              <i class="far fa-bookmark"></i>&nbsp;Save
            </a>
          </div>
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button btn teal my-btn-pad2" id="upvote-button-<ID>" href="#">
              <i class="far fa-comment-dots"></i>&nbsp;25
            </a>
          </div>
          <?php if ($comments == null) { ?>
            <div class="btn-group mt-1 rounded">
              <a class="upvote-button btn teal my-btn-pad2" id="upvote-button-<ID>" href="#">
                <i class="fas fa-reply"></i>&nbsp;Reply
              </a>
            </div>
          <?php } ?>
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button btn blue my-btn-pad2" id="upvote-button-<ID>" href="#">
              <i class="fas fa-share-alt"></i>&nbsp;Share
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer text-muted text-end p-0">
      <blockquote class="blockquote mb-0">
        <p class="card-text px-1">
          <small class="text-muted">34 seconds ago</small>
          <small>
            <a class="signature" href="#">user</a>
          </small>
        </p>
      </blockquote>
    </div>
  </div>
<?php } ?>

<!-- Vertical Buttons -->
<!-- <div class="btn-group-vertical">
  <a href="#" class="btn btn-outline-success border-0 my-btn-pad">
    <i class="fas fa-chevron-up"></i>
  </a>
  <span href="#" class="btn disabled btn-outline-dark border-0 my-btn-pad">29</span>
  <a href="#" class="btn btn-outline-danger border-0 my-btn-pad">
    <i class="fas fa-chevron-down"></i>
  </a>
</div> -->