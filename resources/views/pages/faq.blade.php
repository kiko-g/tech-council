<?php
$questions = [
'How do I ask a question?' => " There is a 'Pose a Question' button on the top of the home page, once clicked you will
be redirected to the question creation page.",
'Can my account be unbanned?' => 'Yes. If one of ours moderators finds that you are in condition to interact once again
with our platform, he has the permission to unban you.',
'Can I delete a question/answer?' => 'Yes. Once you delete a question its content is deleted but answers will have
suffer no changes. If you delete an answer its content is deleted but wisdom points information is kept.',
];
$questionCounter = 0;
?>

@extends('layouts.entry')

@section('content')
  <header class="text-light mb-5 mt-2">
    <h1>Tech Council</h1>
  </header>

  <div class="accordion accordion-flush" id="accordionFAQ">
    <header class="text-start text-light mb-4 ms-4">
      <h3>FAQ</h3>
    </header>

    @foreach ($questions as $question => $answer)
      <?php $questionCounter++; ?>
      <div class="accordion-item btn-light mb-2 border border-5 rounded-3">
        <h2 class="accordion-header" id="heading{{ $questionCounter }}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapse{{ $questionCounter }}" aria-expanded="false"
            aria-controls="collapse{{ $questionCounter }}">
            {{ $question }}
          </button>
        </h2>
        <div id="collapse{{ $questionCounter }}" class="accordion-collapse collapse"
          aria-labelledby="heading{{ $questionCounter }}" data-bs-parent="#accordionFAQ">
          <div class="accordion-body text-start">
            {{ $answer }}
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection
