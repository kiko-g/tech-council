<?php function buildCommentSection($comments, $identifier)
{
	$comment_limit = 3; ?>
	<div class="mb-3">
		<?php
		$comment_count = 0;
		foreach ($comments as $comment) {
			$comment_count++;
		?>
			<div class="<?php if ($comment_count > $comment_limit) { ?> collapse hidden<?= $identifier ?> <?php } ?>">
				<div class="comment d-flex justify-content-between shadow-sm border border-2 mb-1 px-2 bg-light rounded">
					<p class="mb-0"><?= $comment["content"] ?></p>
					<blockquote class="blockquote mb-0">
						<p class="card-text mb-0"><small class="text-muted"><?= $comment["time"] ?>&nbsp;<a class="signature" href="#"><?= $comment["author"] ?></a></small></p>
					</blockquote>
				</div>
			</div>
		<?php }

		if ($comment_count > $comment_limit) { ?>
			<a class="show-more text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden<?= $identifier ?>" aria-expanded="false">Show <?= $comment_count - $comment_limit ?> more comments</a>
			<a class="show-less text-sky me-2 mt-2" data-bs-toggle="collapse" href=".hidden<?= $identifier ?>" aria-expanded="false">Hide <?= $comment_count - $comment_limit ?> last comments</a>
		<?php } ?>

		<form class="collapse" id="collapse<?= $identifier ?>">
			<textarea class="form-control shadow-sm border border-2 bg-light" rows="2" placeholder="Type your comment"></textarea>
				<div class="float-end">
					<a class="btn bg-sky text-white mt-2 me-2" role="button" aria-expanded="false">
						Submit
					</a>
					<a class="btn bg-sky text-white mt-2 me-2" data-bs-toggle="collapse" href="#collapse<?= $identifier ?>" role="button" aria-expanded="false" aria-controls="collapse<?= $identifier ?>">
						Close
					</a>
				</div>
		</form>
		<a class="float-end btn bg-sky text-white add-comment me-2 mt-2" data-bs-toggle="collapse" href="#collapse<?= $identifier ?>" role="button" aria-expanded="false" aria-controls="collapse<?= $identifier ?>">
			Add comment
		</a>
	</div>

<?php } ?>