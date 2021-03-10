<?php function buildTag($comments)
{ ?>
  <div class="card mb-4 p-2-0 border-0 rounded">
    <div class="card-header bg-petrol text-white font-open-sans">
      # javascript
    </div>
    <div class="card-body">
      <p class="mb-3">
        For questions regarding programming in ECMAScript (JavaScript/JS) and its various dialects/implementations (excluding ActionScript).
        Please include all relevant tags on your question; e.g., [node.js], [jquery], [json], etc.
      </p>
      <div class="row row-cols-3 mb-1">

        <div id="interact" class="col-sm-auto flex-wrap">
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button my-btn-pad2 btn btn-outline-success star" id="upvote-button-<ID>" onclick="toggleStar(this)" href="#">
              <i class="far fa-star"></i>&nbsp;Save
            </a>
          </div>
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button btn wine my-btn-pad2" id="upvote-button-<ID>" onclick="/**/" href="#">
              <i class="fas fa-fire"></i>&nbsp;21k
            </a>
          </div>
          <div class="btn-group mt-1 rounded">
            <a class="upvote-button btn blue my-btn-pad2" id="upvote-button-<ID>" onclick="/**/" href="#">
              <i class="fas fa-share-alt"></i>&nbsp;Share
            </a>
          </div>
        </div>
      </div>
      <?php
      if ($comments != null) buildCommentSection($comments, 1);
      ?>
    </div>
  </div>
<?php } ?>