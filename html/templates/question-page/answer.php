<?php function buildAnswer($answer, $identifier)
{ ?>
	<div class="card border-dark mb-3 p-2-0 rounded bg-<?= $answer["correct"] ? 'success' : 'secondary' ?>">
		<div class="card m-1">
			<div class="card-body">
				<div class="fs-5 comment shadow-sm border border-2 p-2 mb-3 bg-light rounded">
					<?= $answer["content"] ?>
				</div>

				<?php buildCommentSection($answer["comments"], $identifier); ?>

				<div class="row row-cols-3">
					<div class="col-sm">
						<div id="votes" class="btn-group border-secondary mt-1 rounded">
							<a class="upvote-button my-btn-pad btn btn-outline-success teal" id="upvote-button-<ID>" onclick="vote('up', this.parentNode)" href="#">
								<i class="fas fa-chevron-up"></i>
							</a>
							<a id="vote-ratio-<ID>" href="#" class="vote-ratio btn btn-secondary my-btn-pad fake disabled">
								73
							</a>
							<a class="downvote-button my-btn-pad btn btn-outline-danger pink" id="downvote-count-<ID>" onclick="vote('down', this.parentNode)" href="#">
								<i class="fas fa-chevron-down"></i>
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