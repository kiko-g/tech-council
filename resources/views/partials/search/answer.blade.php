<section id="search-question-results">
    @if (count($answers) == 0)
        <div class="card">
            <div class="card-body">
                No results
            </div>
        </div>
    @else
        @foreach ($answers as $answer)
            @include('partials.question.answer', ['answer' => $answer ?? '', 'voteValue' => $answer->getVoteValue()])
        @endforeach
        @include('partials.pagination', ['type' => "search-question-"])
    @endif
</section>