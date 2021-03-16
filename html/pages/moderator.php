<?php require_once '../templates/head.php'; ?>

<body>
	<?php require '../templates/header.php'; ?>
	<main class="container">
		<div class="row">
			<div class="moderator-area col-lg-9">
				<div class="users-tags-or-reports-picker">
					<div class="btn-group users-tags-or-reports-button" role="group">
						<button type="button" class="btn active blue-alt users-button">Users</button>
						<button type="button" class="btn blue-alt tags-button">Tags</button>
						<button type="button" class="btn blue-alt reports-button">Reports</button>
					</div>
				</div>
				<div class="user-area">
					<div class="user-search">
						<nav class="user-search-nav navbar navbar-light">
							<form class="container-fluid">
								<div class="input-group">
									<span class="input-group-text" id="basic-addon1"><i class="fas fa-user text-teal-alt"></i></span>
									<input type="text" class="form-control" placeholder="Username" aria-label="Username">
								</div>
							</form>
						</nav>
					</div>
					<div class="ban-users">
						<div class="row">
							<?php
							for ($i = 0; $i < 3; $i++) {
								echo "<div class=\"col-sm\">";
								require '../templates/users/user-card.php';
								echo "</div>";
							}
							?>
						</div>
						<div class="row">
							<?php
							for ($i = 0; $i < 3; $i++) {
								echo "<div class=\"col-sm\">";
								require '../templates/users/user-card.php';
								echo "</div>";
							}
							?>
						</div>
					</div>
					<div class="results-picker">
						<nav>
							<ul class="pagination justify-content-center">
								<li class="page-item">
									<a class="page-link petrol" href="#" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
										<span class="sr-only">Previous</span>
									</a>
								</li>

								<li class="page-item"><a class="page-link petrol" href="#">1</a></li>
								<li class="page-item"><a class="page-link petrol active" href="#">2</a></li>
								<li class="page-item"><a class="page-link petrol" href="#">3</a></li>

								<li class="page-item">
									<a class="page-link petrol" href="#" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">Next</span>
									</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
				<div class="tag-area">
					<div class="tag-search">
						<nav class="tag-search-nav navbar navbar-light">
							<form class="container-fluid">
								<div class="input-group">
									<span class="input-group-text" id="basic-addon1"><i class="fas fa-tag text-dark-green"></i></span>
									<input type="text" class="form-control" placeholder="Tag" aria-label="Tag">
								</div>
							</form>
						</nav>
					</div>
					<div class="moderate-tag container">
						<?php require '../templates/tag-table.php'; ?>
					</div>
					<div class="results-picker">
						<nav>
							<ul class="pagination justify-content-center">
								<li class="page-item">
									<a class="page-link petrol" href="#" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
										<span class="sr-only">Previous</span>
									</a>
								</li>

								<li class="page-item"><a class="page-link petrol" href="#">1</a></li>
								<li class="page-item"><a class="page-link petrol active" href="#">2</a></li>
								<li class="page-item"><a class="page-link petrol" href="#">3</a></li>

								<li class="page-item">
									<a class="page-link petrol" href="#" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">Next</span>
									</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
				<div class="report-area">
					<div class="report-search">
						<nav class="report-search-nav navbar navbar-light py-0">
							<form class="container-fluid">
								<?php require '../templates/filters-reports.php'; ?>
							</form>
						</nav>
					</div>

				</div>
			</div>
			<?php require '../templates/aside.php'; ?>
		</div>

	</main>
	<?php require '../templates/footer.php'; ?>
</body>

</html>