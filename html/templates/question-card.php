<?php function buildQuestion($comments)
{ ?>
  <div class="card mb-5 p-2-0 border-0 rounded">
    <div class="card-header bg-petrol text-white font-open-sans">
      <a class="a header" href="../pages/question.php">
        How do I compare strings in Java?
        <i class="fas fa-link fa-xs text-blue-200 mt-1dot5 ms-2"></i>
      </a>
    </div>
    <div class="card-body">
      <p class="mb-3">
        I've been using the <code>==</code> operator in my program to compare all my strings so far.
        However, I ran into a bug, changed one of them into <code>.equals()</code> instead, and it fixed the bug.

        Is <code>==</code> bad? When should it and should it not be used? What's the difference?
      </p>
      <div class="row row-cols-3 mb-1">
        <div class="col-md flex-wrap">
          <div id="votes" class="votes btn-group-special btn-group-vertical-when-responsive mt-1 flex-wrap">
            <a id="upvote-button" class="upvote-button my-btn-pad rounded-when-responsive up btn btn-outline-success teal" onclick="vote('up', this.parentNode)">
              <i class="fas fa-chevron-up"></i>
            </a>
            <a id="vote-ratio" class="vote-ratio btn btn-secondary my-btn-pad fake disabled"> 42 </a>
            <a id="downvote-button" class="downvote-button my-btn-pad rounded-when-responsive down btn btn-outline-danger pink" onclick="vote('down', this.parentNode)">
              <i class="fas fa-chevron-down"></i>
            </a>
          </div>
        </div>

        <div id="interact" class="col-sm-auto flex-wrap">
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button my-btn-pad2 btn btn-outline-success star" id="upvote-button-<ID>" onclick="toggleStar(this)" href="#">
              <i class="far fa-star"></i>&nbsp;Save
            </a>
          </div>
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button btn wine my-btn-pad2" id="upvote-button-<ID>" href="#">
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

        <div id="tags" class="col-sm-auto">
          <div class="btn-group mt-1">
            <a class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">java</a>
          </div>
          <div class="btn-group mt-1">
            <a class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">node</a>
          </div>
          <div class="btn-group mt-1">
            <a class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">msi</a>
          </div>
          <div class="btn-group mt-1">
            <a class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">nvidia</a>
          </div>
          <div class="btn-group mt-1">
            <a class="btn blue-alt border-0 my-btn-pad2" href="/pages/tag.php">react</a>
          </div>
        </div>
      </div>
      <?php
      if ($comments != null) buildCommentSection($comments, 1);
      ?>
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