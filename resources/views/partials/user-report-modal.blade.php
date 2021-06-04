<div class="modal fade user-report-modal-{{ $user_id }}" id="user-report-modal-{{ $user_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-wine">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-flag text-white"></i>&nbsp;
          Report user {{ $user_id }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="user-report-form-{{ $user_id }}" class="" action="
            @auth {{ url('/api/user/' . $user_id . '/report') }} @endauth
            @guest {{ route('login') }} @endguest">Help us understand the problem. Why are you reporting this user?
          <section class="container px-3 py-2">
            <div class="col">
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="user-report-radio-{{ $user_id }}" id="ur-radio-{{ $user_id }}-1">
                <label class="form-check-label" for="ur-radio-{{ $user_id }}-1">
                  Pretending to be someone else
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="user-report-radio-{{ $user_id }}" id="ur-radio-{{ $user_id }}-2">
                <label class="form-check-label" for="ur-radio-{{ $user_id }}-2">
                  User picture is inappropriate
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="user-report-radio-{{ $user_id }}" id="ur-radio-{{ $user_id }}-3">
                <label class="form-check-label" for="ur-radio-{{ $user_id }}-3">
                  Username is inappropriate 
                </label>
              </div>
              <div class="form-check col">
                <input class="form-check-input report" type="radio" name="user-report-radio-{{ $user_id }}" id="ur-radio-{{ $user_id }}-4">
                <label class="form-check-label" for="ur-radio-{{ $user_id }}-4">
                  Something else
                </label>
              </div>
              <p class="d-none text-red-400 mt-2" id="user-report-{{ $user_id }}-radio-invalid-feeback"> 
                Please select a reason for reporting this user
              </p>
            </div>
          </section>
          <div class="mt-3">
            <textarea class="form-control shadow-sm border border-2 bg-light mt-3" id="user-report-description-{{ $user_id }}" rows="3" 
                placeholder="Talk us through the situation in more detail."></textarea>
            <div class="invalid-feedback">
              Please enter a description with > 10 and < 1000 characters.
          </div>
        </div>
        </form>
      </div>

      <div class="modal-footer">
          <button id="submit-user-report-button-{{ $user_id }}" onclick="submitUserReport(this.id)" type="button" class="submit-user-report-button btn btn blue">Submit</button>
      </div>
    </div>
  </div>
</div>
