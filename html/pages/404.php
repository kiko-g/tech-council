<?php require_once '../templates/head.php'; ?>

<body>
  <main class="container d-flex justify-content-center mt-5">
    <div class="row text-center">
      <div class="error-template">
        <h1 cla> Oops!</h1>
        <h2> 404 Not Found</h2>
        <div class="container-fluid my-5">
          <h5>
            Sorry, an error has occured, Requested page not found!
          </h5>
          <img src="/images/morty.gif" class="img-fluid ms-auto" alt="morty-confused">
        </div>
        <div class="error-actions">
          <a href="/" class="btn btn-primary btn-lg"> Country roads?<br>Take me Home</a>
          <a href="#" class="btn btn-default btn-lg opacity-90">Contact <br>Support <i class="fas fa-ticket-alt text-blue-400"></i> </a>
        </div>
      </div>
    </div>
  </main>
</body>