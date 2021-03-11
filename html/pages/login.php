<?php require_once '../templates/head.php'; ?>

<body>
	<div class="d-flex entry-form flex-column justify-content-center">
		<header class="text-light mb-5">
			<h1><a href="/" class="header">Tech Council</a></h1>
		</header>
		<form>
			<header class="text-start text-light mb-4 ms-4">
				<h3>Sign in</h3>
			</header>
			<div class="form-floating mb-4">
				<input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
				<label for="floatingInput">Email address</label>
			</div>
			<div class="form-floating mb-4">
				<input type="password" class="form-control" id="floatingPassword" placeholder="Password">
				<label for="floatingPassword">Password</label>
			</div>
			<div class="d-flex justify-content-evenly">
				<a href="./register.php" class="link-light entry-anchor">Don't have an account? <br> Sign up</a>
				<button type="submit" class="btn btn-outline-primary btn-light">Submit</button>
			</div>
		</form>
	</div>
	<?php require_once '../templates/footer.php'; ?>
</body>

</html>