<footer class="footer">
  <div class="container-fluid condensed rounded-circle">
    <div class="row p-0">
      {{-- LEFT --}}
      <div class="footer-section rounded-left col-lg-4 col-lg col-12 col p-4">
        <h5 class="footer-section-header">Find us</h5>
        <ul class="footer-ul">
          <li><i class="fa fa-location-arrow"></i>&nbsp; FEUP</li>
          <li><i class="fa fa-phone"></i>&nbsp; +91 999 99 99</li>
          <li><i class="fa fa fa-envelope"></i>&nbsp; lbaw2132@gmail.com</li>
          <li class="border-top-medium text-end pt-1"> Designed using Bootstrap5 by <strong>LBAW 2132</strong>&nbsp;&copy;</li>
        </ul>
      </div>

      {{-- CENTER --}}
      <div class="footer-section col-lg-4 col-lg col-12 col p-4">
        <h5 class="footer-section-header">Quick links</h5>
        <ul class="footer-ul">
          <li><i class="fas fa-home"></i>&nbsp; <a href="{{ route('home') }}">Home</a> </li>
          <li><i class="fas fa-info-circle"></i>&nbsp; <a href="{{ route('about') }}">About</a></li>
          <li><i class="fas fa-question-circle"></i>&nbsp; <a href="{{ route('faq') }}">FAQ</a></li>
        </ul>
      </div>

      {{-- RIGHT --}}
      <div class="footer-section rounded-right col-lg-4 col-lg col-12 col p-4">
        <h5 class="footer-section-header">Follow us</h5>
        <ul class="footer-ul">
          <li><i class="fa fa-github"></i>&nbsp; <a target="_blank" href="https://github.com/ffriande">Francisco Friande </a></li>
          <li><i class="fa fa-github"></i>&nbsp; <a target="_blank" href="https://github.com/kiko-g">Francisco Gonçalves </a></li>
          <li><i class="fa fa-github"></i>&nbsp; <a target="_blank" href="https://github.com/TsarkFC">João Romão </a></li>
          <li><i class="fa fa-github"></i>&nbsp; <a target="_blank" href="https://github.com/migueldelpinto">Miguel Pinto </a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
