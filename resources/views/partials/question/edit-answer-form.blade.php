@auth
  @if (Auth::user()->id == $answer->content->author_id)
    <div class="col-9 col-sm-10 col-md-11 col-lg-11 flex-wrap pe-0 collapse answer-collapse-{{ $answer->content_id }}">
      <form class="answer-collapse-{{ $answer->content_id }} container ps-0 answer-edit-form"
        id="answer-edit-form-{{ $answer->content_id }}" method="post">
        @method('PUT')
        @csrf
        <div class="row row-cols-2">
          {{-- edit text area --}}
          <div id="{{ 'answer-content-' . $answer->content_id }}" class="mb-1 col-10 me-auto p-0">
            <textarea id="answer-submit-input-{{ $answer->content_id }}" name="main"
              class="form-control border border-2 bg-light" rows="5" placeholder="Type your answer">
                      {!! $answer->content->main !!}
                  </textarea>
          </div>

          <div class="col-1 p-0 m-0 collapse answer-control answer-collapse-{{ $answer->content_id }}"
            id="answer-control-{{ $answer->content_id }}">
            <div class="btn-group float-end">
              <button class="btn p-0" type="submit" data-bs-toggle="collapse"
                data-bs-target=".answer-collapse-{{ $answer->content_id }}" aria-expanded="true"
                aria-controls="answer-content-{{ $answer->content_id }} answer-control-{{ $answer->content_id }}">
                <i class="fas fa-check text-teal-300 mt-1 ms-2"></i>
              </button>
              <button class="btn p-0" type="button" data-bs-toggle="collapse"
                data-bs-target=".answer-collapse-{{ $answer->content_id }}" aria-expanded="true"
                aria-controls="answer-content-{{ $answer->content_id }} answer-control-{{ $answer->content_id }}">
                <i class="fas fa-close text-wine mt-1 ms-2"></i>
              </button>
            </div>
          </div>
          <div>
      </form>
    </div>
  @endif
@endauth
