<?php
include_once '../templates/question-card.php';
require '../templates/head.php';
?>

<body>
	<?php require '../templates/header.php'; ?>
	<main class="container">
		<div class="row" id="profile-row">
			<article class="col-lg-9 order-last order-lg-first">
				<?php require '../templates/filters-profile.php'; ?>
				<div>
					<?php
					for ($i = 0; $i < 5; $i++) {
						buildQuestion(null);
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
			<?php require '../templates/users/user-aside.php'; ?>
		</div>
	</main>
	<?php require '../templates/footer.php'; ?>
</body>

</html>