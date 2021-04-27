@include('partials.question.comment-section')
@include('partials.question.answer')
@include('partials.question.card')
<?php $question = [
'comments' => [
[
'content' => 'Hey my 9 year old daughter likes it',
'author' => 'fiambre',
'time' => '2 hours ago',
],
[
'content' => 'I agree with you, Windows is awful',
'author' => 'queijo',
'time' => '1 hour ago',
],
],
'answers' => [
[
'content' => 'Resposta',
'correct' => true,
'comments' => [
[
'content' => 'Nice way to put it, are you a teacher?',
'author' => 'fiambre',
'time' => '2 hours ago',
],
[
'content' => 'Yes! At FEUP',
'author' => 'jlopes',
'time' => '1 hour ago',
],
[
'content' => 'Hi professor!',
'author' => 'jdiogueiro',
'time' => '30 mins ago',
],
],
],
[
'content' => "I don't know what to say",
'correct' => false,
'comments' => [
[
'content' => 'Why even bother answering?',
'author' => 'fiambre',
'time' => '2 hours ago',
],
],
],
[
'content' => 'A question with a lot of comments',
'correct' => false,
'comments' => [
[
'content' => 'First',
'author' => 'fiambre',
'time' => '2 hours ago',
],
[
'content' => 'First',
'author' => 'jdiogueiro',
'time' => '2 hours ago',
],
[
'content' => 'Hope your answer is deleted',
'author' => 'jlopes',
'time' => '2 hours ago',
],
[
'content' => 'Second',
'author' => 'mpinto01',
'time' => '2 hours ago',
],
[
'content' => 'Why?',
'author' => 'kikojpg',
'time' => '2 hours ago',
],
],
],
],
]; ?>

<div>
  <?php
  buildQuestion($question['comments']);
  $comment_section_counter = 2;
  ?>
  @include('partials.question.answer-submit')

  @foreach ($question['answers'] as $answer)
    <?php buildAnswer($answer, $comment_section_counter++); ?>
  @endforeach
</div>
