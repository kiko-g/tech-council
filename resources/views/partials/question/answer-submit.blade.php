<div id="answer-submit-card" class="card border-0 mb-4">
  <div class="card-body">
    <form id="answer-submit-form" data-question-id="{{ $question_id }}" action="{{ route('answer.create', $question_id) }}" method="post">
      @csrf
      <textarea id="answer-submit-input" name="main" class="form-control shadow-sm border border-2 bg-light" rows="2"
        placeholder="Type your answer"></textarea>
      <button id="answer-submit-button" name="submit" class="btn blue-alt extra text-white float-end mt-3" type="submit">
        Answer
      </button>
    </form>
  </div>
</div>
