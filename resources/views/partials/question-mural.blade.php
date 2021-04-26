@include('partials.question-comment-section')
@include('partials.question-answer')
@include('partials.question-card')
<?php

$question = array(
  "comments" => array(
    array(
      "content" => "Hey my 9 year old daughter likes it",
      "author" => "fiambre",
      "time" => "2 hours ago"
    ),
    array(
      "content" => "I agree with you, Windows is awful",
      "author" => "queijo",
      "time" => "1 hour ago"
    )
  ),
  "answers" => array(
    array(
      "content" => "Resposta",
      "correct" => true,
      "comments" => array(
        array(
          "content" => "Nice way to put it, are you a teacher?",
          "author" => "fiambre",
          "time" => "2 hours ago"
        ),
        array(
          "content" => "Yes! At FEUP",
          "author" => "jlopes",
          "time" => "1 hour ago"
        ),
        array(
          "content" => "Hi professor!",
          "author" => "jdiogueiro",
          "time" => "30 mins ago"
        )
      )
    ),
    array(
      "content" => "I don't know what to say",
      "correct" => false,
      "comments" => array(
        array(
          "content" => "Why even bother answering?",
          "author" => "fiambre",
          "time" => "2 hours ago"
        )
      )
    ),
    array(
      "content" => "A question with a lot of comments",
      "correct" => false,
      "comments" => array(
        array(
          "content" => "First",
          "author" => "fiambre",
          "time" => "2 hours ago"
        ),
        array(
          "content" => "First",
          "author" => "jdiogueiro",
          "time" => "2 hours ago"
        ),
        array(
          "content" => "Hope your answer is deleted",
          "author" => "jlopes",
          "time" => "2 hours ago"
        ),
        array(
          "content" => "Second",
          "author" => "mpinto01",
          "time" => "2 hours ago"
        ),
        array(
          "content" => "Why?",
          "author" => "kikojpg",
          "time" => "2 hours ago"
        )
      )
    )
  )
);
?>

<div>
  <?php 
  buildQuestion($question["comments"]);
  $comment_section_counter = 2; 
  ?>
  @include('partials.question-answer-submit')
  
  @foreach($question["answers"] as $answer)
    <?php buildAnswer($answer, $comment_section_counter++); ?>      
  @endforeach
</div>