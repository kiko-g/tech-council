<div class="modal fade report-modal" id="report-modal-{{ $content_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-1">
      <div class="modal-header bg-wine">
        <h5 class="modal-title" id="report-modal-label-{{ $content_id }}"><i class="fas fa-flag text-white"></i>&nbsp;
          Report {{ $type }}
        </h5>
        <button type="button" class="btn-close" id="btn-close-content-{{ $content_id }}" data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="report-form-{{ $content_id }}" class="" action="
            @auth {{ url('/api/content/' . $content_id . '/report') }} @endauth
            @guest {{ route('login') }} @endguest">Help us understand the problem. What is going on with this
          {{ $type }}?
          <section class="container px-3 py-2">
            <div class="col">
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="report-radio-{{ $content_id }}"
                  id="radio-{{ $content_id }}-1">
                <label class="form-check-label" for="radio-{{ $content_id }}-1">
                  It's suspicious or spam
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="report-radio-{{ $content_id }}"
                  id="radio-{{ $content_id }}-2">
                <label class="form-check-label" for="radio-{{ $content_id }}-2">
                  It displays sensitive content
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="report-radio-{{ $content_id }}"
                  id="radio-{{ $content_id }}-3">
                <label class="form-check-label" for="radio-{{ $content_id }}-3">
                  It shows incorrect information
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="report-radio-{{ $content_id }}"
                  id="radio-{{ $content_id }}-4">
                <label class="form-check-label" for="radio-{{ $content_id }}-4">
                  It's abusive or harmful
                </label>
              </div>
            </div>
            <p class="d-none text-red-400 mt-2" id="report-{{ $content_id }}-radio-invalid-feeback">
              Please select a reason for reporting this content
            </p>
          </section>
          <textarea class="form-control border border-2 bg-light mt-3" id="report-description-{{ $content_id }}"
          rows="3" placeholder="Talk us through the situation in more detail."></textarea>
          <div class="invalid-feedback"> Please enter a description with &gt; 10 and &lt; 1000 characters. </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="submit-report-button-{{ $content_id }}" onclick="submitContentReport(this.id)" type="button"
          class="submit-report-button btn blue">Submit</button>
      </div>
    </div>
  </div>
</div>