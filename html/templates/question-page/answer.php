<?php function buildAnswer($answer, $identifier)
{ ?>
	<div class="card border-dark mb-3 p-2-0 rounded bg-<?= $answer["correct"] ? 'success' : 'sky' ?>">
		<div class="card m-2">
			<div class="card-body">
				<div class="fs-5 comment shadow-sm border border-2 p-2 mb-3 bg-light rounded">
					<?= $answer["content"] ?>
				</div>

				<?php buildCommentSection($answer["comments"], $identifier); ?>

				<div class="row row-cols-3">
					<div class="col-sm">
						<div id="votes" class="btn-group border-secondary mt-1 rounded">
							<a class="upvote-button btn btn-outline-success my-btn-pad" id="upvote-button-<ID>" onclick="upvote(this)" href="#">
								<i class="fas fa-chevron-up"></i>
							</a>
							<a id="vote-ratio-<ID>" href="#" class="vote-ratio btn btn-secondary my-btn-pad fake disabled">
								29
							</a>
							<a class="downvote-button btn btn-outline-danger my-btn-pad" id="downvote-count-<ID>" onclick="downvote(this)" href="#">
								<i class="fas fa-chevron-down"></i>
							</a>
						</div>
					</div>

					<div id="interact" class="col-sm-auto">
						<div class="btn-group mt-1 rounded">
							<a class="upvote-button btn orange my-btn-pad2" id="upvote-button-<ID>" onclick="upvote(this)" href="#">
								<i class="far fa-comment-dots"></i>&nbsp;25
							</a>
						</div>
						<div class="btn-group mt-1 rounded">
							<a class="upvote-button btn orange my-btn-pad2" id="upvote-button-<ID>" onclick="upvote(this)" href="#">
								<i class="fas fa-reply"></i>&nbsp;25
							</a>
						</div>
						<div class="btn-group mt-1 rounded">
							<a class="upvote-button btn orange my-btn-pad2" id="upvote-button-<ID>" onclick="upvote(this)" href="#">
								<i class="fas fa-share"></i>&nbsp;Share
							</a>
						</div>
					</div>
				</div>

			</div>
			<div class="card-footer text-muted text-end p-0">
				<blockquote class="blockquote mb-0">
					<p class="card-text px-1"><small class="text-muted">34 seconds ago&nbsp;<a class="signature" href="#">user</a></small></p>
				</blockquote>
			</div>
		</div>
	</div>

<?php } ?>