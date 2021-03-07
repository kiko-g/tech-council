<div class="card mb-3 p-2-0 border-0 rounded">
  <div class="card-header bg-sky text-white font-open-sans"> Is Java a good programming language? </div>
  <div class="card-body">
    <p class="card-title font-open-sans">Brief description for card question</p>
    <div class="row row-cols-3">
      <div class="col-sm">
        <div id="votes" class="votes btn-group mt-1">
          <a class="upvote-button my-btn-pad btn btn-outline-success teal" id="upvote-button-<ID>" onclick="vote('up', this.parentNode)" href="#">
            <i class="fas fa-chevron-up"></i>
          </a>
          <a id="vote-ratio-<ID>" href="#" class="vote-ratio btn btn-secondary my-btn-pad fake disabled">
            29
          </a>
          <a class="downvote-button my-btn-pad btn btn-outline-danger pink" id="downvote-count-<ID>" onclick="vote('down', this.parentNode)" href="#">
            <i class="fas fa-chevron-down"></i>
          </a>
        </div>
      </div>

      <div id="interact" class="col-sm-auto">
        <div class="btn-group mt-1 rounded">
          <a class="upvote-button btn red my-btn-pad2" id="upvote-button-<ID>" onclick="upvote(this)" href="#">
            <i class="far fa-comment-dots"></i>&nbsp;25
          </a>
        </div>
        <div class="btn-group mt-1 rounded">
          <a class="upvote-button btn teal my-btn-pad2" id="upvote-button-<ID>" onclick="upvote(this)" href="#">
            <i class="fas fa-reply"></i>&nbsp;Reply
          </a>
        </div>
        <div class="btn-group mt-1 rounded">
          <a class="upvote-button btn blue my-btn-pad2" id="upvote-button-<ID>" onclick="upvote(this)" href="#">
            <i class="fas fa-share"></i>&nbsp;Share
          </a>
        </div>
      </div>

      <div id="tags" class="col-sm-auto">
        <div class="btn-group mt-1">
          <a href="#" class="btn btn-secondary border-0 my-btn-pad2">java</a>
        </div>
        <div class="btn-group mt-1">
          <a href="#" class="btn btn-secondary border-0 my-btn-pad2">node</a>
        </div>
        <div class="btn-group mt-1">
          <a href="#" class="btn btn-secondary border-0 my-btn-pad2">msi</a>
        </div>
        <div class="btn-group mt-1">
          <a href="#" class="btn btn-secondary border-0 my-btn-pad2">nvidia</a>
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