<div class="modal fade" id="report-modal-{{ $type }}-{{ $content_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="far fa-flag text-wine"></i>&nbsp;&nbsp;Report {{ $type }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="report-form-{{ $content_id }}" class="" action="
            @auth 
              {{ url('/api/content/' . $content_id . '/report') }}
            @endauth
            @guest
              {{ route('login') }}
            @endguest">

          <div class="modal-body">
            Please select the reason why you are reporting this content:
          <section class="container px-3 py-2">
            <div class="row">
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio-{{ $content_id }}" id="radio-{{ $content_id }}-1">
                <label class="form-check-label" for="radio-{{ $content_id }}-1">
                  Violence
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio-{{ $content_id }}" id="radio-{{ $content_id }}-2">
                <label class="form-check-label" for="radio-{{ $content_id }}-2">
                  Harassment
                </label>
              </div>
            </div>
            <div class="row">
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio-{{ $content_id }}" id="radio-{{ $content_id }}-3">
                <label class="form-check-label" for="radio-{{ $content_id }}-3">
                  False Information
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio-{{ $content_id }}" id="radio-{{ $content_id }}-4">
                <label class="form-check-label" for="radio-{{ $content_id }}-4">
                  Spam
                </label>
              </div>
            </div>
            <div class="row">
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio-{{ $content_id }}" id="radio-{{ $content_id }}-5">
                <label class="form-check-label" for="radio-{{ $content_id }}-5">
                  Hate Speech
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio-{{ $content_id }}" id="radio-{{ $content_id }}-6">
                <label class="form-check-label" for="radio-{{ $content_id }}-6">
                  Inappropriate Content
                </label>
              </div>
            </div>
          </section>
					<textarea class="form-control shadow-sm border border-2 bg-light mt-3" id ="report-description-{{ $content_id }}" rows="3" placeholder="Describe the reason to report this {{ $type }}"></textarea>
          </div>
          <div class="modal-footer">
            <button id="submit-report-button-{{ $content_id }}" type="button" class="submit-report-button btn btn-secondary wine">Report</button>
          </div>
        </form>

    </div>
  </div>
</div>
