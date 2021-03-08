<?php require_once '../templates/head.php'; ?>

<body>
    <?php require '../templates/header.php'; ?>
    <div class="search-results-header">
        <main class="container">
            <div class="row justify-content-between search-and-pose">
                <div class="col-12 my-auto">
                    <h5>
                        Search Results for "windows"
                        <!--<small class="text-muted">for windows</small>-->
                    </h5>
                    <h8>
                        [163 results]
                    </h8>
                </div>
            </div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-questions-tab" data-bs-toggle="tab" data-bs-target="#nav-questions" type="button" role="tab">Questions</button>
                    <button class="nav-link" id="nav-tags-tab" data-bs-toggle="tab" data-bs-target="#nav-tags" type="button" role="tab">Tags</button>
                    <button class="nav-link" id="nav-users-tab" data-bs-toggle="tab" data-bs-target="#nav-users" type="button" role="tab">Users</button>
                </div>
            </nav>
        </main>
    </div>
        
    <main class="container">
        <div class="row">
            <div class="col-lg-9 search-results">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-questions" role="tabpanel">
                        <?php require '../templates/filters.php'; ?>
                        <div>
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                require '../templates/question-card.php';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-tags" role="tabpanel">
                        ...
                    </div>
                    <div class="tab-pane fade" id="nav-users" role="tabpanel">
                        ...
                    </div>
                </div>
            </div>            
            <?php require '../templates/aside.php'; ?>
        </div>
        
        
    </main>
    <?php require '../templates/footer.php'; ?>
</body>

</html>