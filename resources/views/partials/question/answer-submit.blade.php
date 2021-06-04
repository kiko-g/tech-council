<script defer src="{{ asset('js/stackedit-alt.js') }}"></script>
<div id="answer-submit-card" class="card border-0 mt-0 mb-5">
  <div class="card-body">
    <form id="answer-submit-form" data-question-id="{{ $question_id }}" action="{{ route('answer.create', $question_id) }}" method="post">
      @csrf
      <div class="textarea-container">
        <textarea id="answer-submit-input" class="form-control border border-2 bg-light mb-2" name="main" rows="4" placeholder="Type your answer"></textarea>
        <button id="toggle-stackedit" class="btn btn blue toggle-stackedit off" type="button" data-bs-original-title="Switch to stackedit">StackEdit</button>
      </div>      
      <button id="answer-submit-button" name="submit" class="btn blue-alt extra text-white float-end mt-3 px-2 py-1" type="submit"> Answer </button>
    </form>
  </div>
</div>
