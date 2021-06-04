<section id="search-question-results">
  @foreach ($questions as $question)
    @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
  @endforeach
  @include('partials.pagination', ['type' => "search-question-"])
</section>
