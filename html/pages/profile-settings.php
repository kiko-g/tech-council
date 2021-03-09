<?php require_once '../templates/head.php'; ?>

<body>
    <?php require '../templates/header.php'; ?>
    <main class="container">
        <div class="row">
            <div class="moderator-area col-lg-9">

                <header class="mb-4">
                    <h2 class="title"> <i class="fas fa-cog"></i> User Settings</h2>
                </header>

                <form class="ms-4">
                    <header>
                        <h6 class="title">Username</h6>
                    </header>
                    <div class="mb-4 row">
                        <input type="email" class="form-control col" placeholder="Username" value="Jlopes" disabled>
                        <button class="btn btn-outline-primary btn-light col-md-auto mx-1" onclick="f(this)"
                            href="#">Change</button>
                    </div>

                    <header>
                        <h6 class="title">E-mail</h6>
                    </header>
                    <div class="mb-4 row">
                        <input type="email" class="form-control col" placeholder="name@example.com"
                            value="jlopes@bilbaw.com" disabled>
                        <button class="btn btn-outline-primary btn-light col-md-auto mx-1" onclick="f(this)"
                            href="#">Change</button>
                    </div>

                    <header>
                        <h6 class="title">Picture</h6>
                    </header>
                    <div class="mb-5 position-relative">
                        <img src="../images/kermy.jpeg" class="" alt="kermy" width="150" height="160">
                        <label class="btn btn-outline-primary btn-light position-absolute bottom-0 mx-2">
                            Change <input type="file" hidden>
                        </label>
                    </div>
                </form>
                <div class="ms-2">
                    <button class="btn btn-light col-md-auto mx-1 mb-2" onclick="f(this)" href="#">Change
                        Password</button>

                    <div class="m-2">
                        <a class="link-danger entry-anchor">Delete Account</a>
                    </div>
                </div>

            </div>

            <?php require '../templates/aside-prof.php'; ?>
        </div>

    </main>
    <?php require '../templates/footer.php'; ?>
</body>

</html>