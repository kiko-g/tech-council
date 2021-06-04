<div class="modal fade best-modal border-0" id="best-answer-modal-{{ $content_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-teal">
        <h5 class="modal-title text-white" id="modalLabel"><i class="fa fa-check-circle text-white"></i>&nbsp;
          Set Best Answer
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <section class="container px-3 py-2">
          <div class="col">
            Are you sure you want to make this the corret answer? This action is irreversible and will "close" this thread.
          </div>
        </section>
      </div>

      <div class="modal-footer">
        <button id="submit-best-button-{{ $content_id }}" onclick="submitBestAnswer(this.id)"
          type="button" class="submit-best-button btn btn blue">Yes, make this answer permanently correct.</button>
      </div>
    </div>
  </div>
</div>
