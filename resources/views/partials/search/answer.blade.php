<section id="search-question-results">
  @if (count($answers) == 0)
    @include('partials.search.noresults')
  @else
    @foreach ($answers as $answer)
      @include('partials.question.answer', ['answer' => $answer ?? '', 'voteValue' => $answer->getVoteValue()])
    @endforeach
    @include('partials.pagination', ['type' => "search-question-"])
  @endif
</section>
