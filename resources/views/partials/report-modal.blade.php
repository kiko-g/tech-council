@php
  $contentWasReported = false;
@endphp
<div class="modal fade" id="report-modal-{{ $type }}-{{ $content_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="far fa-flag text-wine"></i>&nbsp;&nbsp;
          @if($contentWasReported) Edit {{ $type }} report
          @else Report {{ $type }} @endif
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">Please select the reason why you are reporting this content:
        <form id="report-form-{{ $content_id }}" class="" action="
          @auth {{ url('/api/content/' . $content_id . '/report') }} @endauth
          @guest {{ route('login') }} @endguest">
          <section class="container px-3 py-2">
            <div class="row">
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio" id="reportradio1">
                <label class="form-check-label" for="reportradio1"> Violence </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio" id="reportradio2">
                <label class="form-check-label" for="reportradio2"> Harassment </label>
              </div>
            </div>
            <div class="row">
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio" id="reportradio3">
                <label class="form-check-label" for="reportradio3"> False Information </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio" id="reportradio4">
                <label class="form-check-label" for="reportradio4"> Spam </label>
              </div>
            </div>
            <div class="row">
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio" id="reportradio5">
                <label class="form-check-label" for="reportradio5">
                  Hate Speech
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input" type="radio" name="report-radio" id="reportradio6">
                <label class="form-check-label" for="reportradio6"> Inappropriate Content </label>
              </div>
            </div>
          </section>
					<textarea class="form-control shadow-sm border border-2 bg-light mt-3" rows="3" placeholder="Describe the reason to report this {{ $type }}"></textarea>
        </form>
      </div>

      <div class="modal-footer">
          @if($contentWasReported) 
          <button id="submit-report-button-{{ $content_id }}" onclick="@php $contentWasReported = true; @endphp deleteReport()" type="button" class="btn btn-secondary wine">Delete</button>
          @else 
          @endif
          <button id="submit-report-button-{{ $content_id }}" onclick="@php $contentWasReported = true; @endphp submitReport()" type="button" class="btn btn blue">Submit</button>
      </div>
    </div>
  </div>
</div>
