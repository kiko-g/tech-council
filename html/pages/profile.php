<?php
include_once '../templates/question-card.php';
require '../templates/head.php';
?>

<body>
	<?php require '../templates/header.php'; ?>
	<main class="container">
		<div class="row">
			<article class="col-lg-9">
				<div>
					<?php
					for ($i = 0; $i < 5; $i++) {
						buildQuestion(null);
					}
					?>
				</div>
				<nav>
					<ul class="pagination justify-content-center">
						<li class="page-item"><a class="page-link" href="#">Previous</a></li>
						<li class="page-item"><a class="page-link" href="#">1</a></li>
						<li class="page-item"><a class="page-link" href="#">2</a></li>
						<li class="page-item"><a class="page-link" href="#">3</a></li>
						<li class="page-item"><a class="page-link" href="#">Next</a></li>
					</ul>
				</nav>
			</article>
			<?php require '../templates/aside-profile.php'; ?>
		</div>
	</main>
	<?php require '../templates/footer.php'; ?>
</body>

</html>