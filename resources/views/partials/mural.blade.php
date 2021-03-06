@include('partials.filters.question', ['filter_prefix' => "main"])

@foreach ($questions as $question)
  @include('partials.question.card', ['question' => $question, 'include_comments' => false, 'voteValue' => $question->getVoteValue()])
@endforeach

@include('partials.pagination')
