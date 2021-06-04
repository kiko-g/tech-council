<nav class="navbar-dark bg-petrol mb-3 rounded p-2">
  <div class="btn-toolbar justify-content-between px-1">
    {{-- TODO: select this button group with JS to select what is being displayed --}}
    <div class="btn-group" role="group" aria-label="Radio toggle button group">
      <input type="radio" class="btn-check" name="radio button" id="filterRadio1_saved" checked>
      <label class="btn blue-alt" for="filterRadio1_saved">
        <i class="fas fa-bookmark fa-sm text-red-400"></i>&nbsp;Saved
        {{-- <span class="badge align-middle">347</span> --}}
      </label>

      <input type="radio" class="btn-check" name="radio button" id="filterRadio2_myquestions">
      <label class="btn blue-alt" for="filterRadio2_myquestions">
        <i class="fas fa-question-circle fa-sm text-teal-200"></i>&nbsp;My Questions
      </label>

      <input type="radio" class="btn-check" name="radio button" id="filterRadio3_myanswers">
      <label class="btn blue-alt" for="filterRadio3_myanswers">
        <i class="fas fa-hands-helping fa-sm text-blue-100"></i>&nbsp;My Answers
      </label>
    </div>
  </div>
</nav>
