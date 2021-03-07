<?php
include_once 'comment-section.php';
include_once 'answer.php';
include_once __DIR__ . '/../question-card.php';

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
      "content" => "The answer to this question is that 'it is impossible'. you can write a regular expression that accepts all palindromes that are smaller than some finite fixed length.",
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


<article class="col-lg-9 ">
  <div>
    <?php
    buildQuestion($question["comments"]);
    require_once 'answer-submit.php';
    $comment_section_counter = 2;
    foreach ($question["answers"] as $answer) {
      buildAnswer($answer, $comment_section_counter++);
    }
    ?>
  </div>
</article>