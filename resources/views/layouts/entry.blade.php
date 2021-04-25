@include('partials.head')
<body>
	@include('partials.header')
	<div class="d-flex entry-form flex-column justify-content-center border-top-bg">
        <section id="content">
            @yield('content')
        </section>
	</div>
	@include('partials.footer')
</body>

</html>
