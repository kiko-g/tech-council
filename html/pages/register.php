<?php require_once '../templates/head.php'; ?>

<body>
	<div class="d-flex entry-form flex-column justify-content-center">
		<header class="text-light mb-4">
			<h1>Tech Council</h1>
		</header>
		<form>
			<header class="text-start text-light mb-4 ms-4">
				<h3>Sign up</h3>
			</header>
			<div class="form-floating mb-4">
				<input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
				<label for="floatingInput">Email address</label>
			</div>
			<div class="form-floating mb-4">
				<input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
				<label for="floatingInput">Username</label>
			</div>
			<div class="form-floating mb-4">
				<input type="password" class="form-control" id="floatingPassword" placeholder="Password">
				<label for="floatingPassword">Password</label>
			</div>
			<div class="form-floating mb-4">
				<input type="password" class="form-control" id="floatingPassword" placeholder="Password">
				<label for="floatingPassword">Confirm password</label>
			</div>
			<div class="d-flex justify-content-evenly">
				<a href="./login.php" class="link-light entry-anchor">Already have an account? <br> Sign in</a>
				<button type="submit" class="btn btn-outline-primary btn-light">Submit</button>
			</div>
		</form>
	</div>
	<?php require_once '../templates/footer.php'; ?>
</body>

</html>