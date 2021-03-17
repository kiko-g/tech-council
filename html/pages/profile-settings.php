<?php require_once '../templates/head.php'; ?>

<body>
	<?php require '../templates/header.php'; ?>
	<main class="container">
		<div class="row">

			<article class="col-lg-9">
				<div class="card mb-3">
					<div class="card-header text-white bg-petrol font-open-sans"> User Settings </div>
					<div class="row g-0">
						<div class="col-lg-4">
							<img src="../images/dwight.png" class="card-img-top rounded p-3" alt="...">
						</div>
						<div class="col-lg-8">
							<div class="card-body">
								<h5 class="card-title">Card title</h5>
								<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
								<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
								<a href="#" class="btn blue">Change photo</a>
							</div>
						</div>

						<div class="card-body">
							<form class="row g-3">
								<div class="col-lg-6">
									<label for="inputEmail4" class="form-label">Email</label>
									<input type="email" class="form-control" id="inputEmail4">
								</div>
								<div class="col-lg-6">
									<label for="inputPassword4" class="form-label">Password</label>
									<input type="password" class="form-control" id="inputPassword4">
								</div>
								<div class="col-12">
									<label for="inputAddress" class="form-label">Address</label>
									<input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
								</div>
								<div class="col-12">
									<label for="inputAddress2" class="form-label">Address 2</label>
									<input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
								</div>
								<div class="col-lg-6">
									<label for="inputCity" class="form-label">City</label>
									<input type="text" class="form-control" id="inputCity">
								</div>
								<div class="col-lg-4">
									<label for="inputState" class="form-label">State</label>
									<select id="inputState" class="form-select">
										<option selected>Choose...</option>
										<option>...</option>
									</select>
								</div>
								<div class="col-lg-2">
									<label for="inputZip" class="form-label">Zip</label>
									<input type="text" class="form-control" id="inputZip">
								</div>
								<div class="col-12">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="gridCheck">
										<label class="form-check-label" for="gridCheck">
											Check me out
										</label>
									</div>
								</div>
								<div class="col-12 text-end">
									<button type="submit" class="btn teal">Save changes</button>
								</div>
							</form>
						</div>
					</div>
				</div>

			</article>

			<aside class="col-lg-3 mb-3">
				<div class="card mb-3">
					<div class="card-header text-white bg-petrol font-open-sans"> Support </div>
					<div class="card-body">
						<h5 class="card-title">Need help?</h5>
						<p class="card-text">Don't hesitate hitting us up.</p>
						<a href="#" class="btn blue">Contact us</a>
					</div>
				</div>

				<div class="card mb-3">
					<div class="card-header text-white bg-petrol font-open-sans"> Other options </div>
					<div class="card-body">
						<a href="#" class="btn blue"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
					</div>
				</div>
			</aside>
		</div>
	</main>
	<?php require '../templates/footer.php'; ?>
</body>

</html>