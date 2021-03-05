<?php require_once '../templates/head.php'; ?>

<body>
    <?php require '../templates/header.php'; ?>
    <main>
        <section class="moderator-area">
            <section class="users-or-tags-picker">
                <div class="btn-group users-or-tags-button" role="group">
                    <button type="button" class="btn active btn-secondary users-button">Users</button>
                    <button type="button" class="btn btn-secondary tags-button">Tags</button>
                </div>
            </section>
            <section class="user-search">
                <nav class="user-search-nav navbar navbar-light">
                    <form class="container-fluid">
                        <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username">
                        </div>
                    </form>
                </nav>
            </section>
            <section class="ban-area">
                <?php
                    for ($i = 0; $i < 6; $i++) {
                        require '../templates/user-card.php';
                    }
                ?>
            </section>
            <section class="results-picker">
                <nav>
                    <ul class="pagination">
                        <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                        </li>
                    </ul>
                </nav>
            </section>
        </section>
        <?php require '../templates/aside.php'; ?>
    </main> 
    <?php require '../templates/footer.php'; ?>
</body>

</html>