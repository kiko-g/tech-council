<section id="search-question-results">
  @if (count($questions) === 0)
    <div class="card">
      <div class="card-body">
          No results
      </div>
  </div>
  @else
    @foreach ($questions as $question)
      @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
    @endforeach
    @include('partials.pagination', ['type' => "search-question-"]) 
  @endif
</section>
