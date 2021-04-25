@include('partials.head')
@include('partials.question-card')

<body>
	@include('partials.header')
	<main class="container">
		<div class="row" id="profile-row">
			<article class="col-lg-9 order-last order-lg-first">
                @include('partials.filters-profile')
				<div>
					@for($i = 0; $i < 5; $i++)
                        <?php 
                        buildQuestion(null); 
                        ?>
                    @endfor
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
            @include('partials.user-aside')
		</div>
	</main>
    @include('partials.footer')
</body>

</html>