@include('partials.filters.question')

<div>
  @foreach ($questions as $question)
    @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
  @endforeach
</div>

@include('partials.pagination')
