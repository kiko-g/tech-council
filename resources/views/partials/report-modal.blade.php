<div class="modal fade" id="report-modal-{{ $type }}-{{ $content_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-flag"></i>&nbsp;Report {{ $type }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Please select the reason why you are reporting this content:
        <form id="report-form-{{ $content_id }}" action="
            @auth 
              {{ route('report/question') }}
            @endauth
            @guest
              {{ route('login') }}
            @endguest">
          <div class="row ms-3">
            <div class="form-check col">
              <input class="form-check-input" type="radio" name="report-radio" id="radio1">
              <label class="form-check-label" for="radio1">
                Violence
              </label>
            </div>
            <div class="form-check col">
              <input class="form-check-input" type="radio" name="report-radio" id="radio2">
              <label class="form-check-label" for="radio2">
                Harassment
              </label>
            </div>
          </div>
          <div class="row ms-3">
            <div class="form-check col">
              <input class="form-check-input" type="radio" name="report-radio" id="radio3">
              <label class="form-check-label" for="radio3">
                False Information
              </label>
            </div>
            <div class="form-check col">
              <input class="form-check-input" type="radio" name="report-radio" id="radio4">
              <label class="form-check-label" for="radio4">
                Spam
              </label>
            </div>
          </div>
          <div class="row ms-3">
            <div class="form-check col">
              <input class="form-check-input" type="radio" name="report-radio" id="radio5">
              <label class="form-check-label" for="radio5">
                Hate Speech
              </label>
            </div>
            <div class="form-check col">
              <input class="form-check-input" type="radio" name="report-radio" id="radio6">
              <label class="form-check-label" for="radio6">
                Inappropriate Content
              </label>
            </div>
          </div>
					<textarea class="form-control shadow-sm border border-2 bg-light" rows="2" placeholder="Describe the reason to report this {{ $type }}"></textarea>
        </form>
      </div>

      <div class="modal-footer">
        <button id="submit-report-button-{{ $content_id }}" type="button" class="btn btn-secondary red disabled">Report</button>
      </div>
    </div>
  </div>
</div>
