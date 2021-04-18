/*  INSERT01 
 *  Register a new user
 *  Dozens per day
 */
INSERT INTO "user" (email, name, password)
VALUES ($email, $name, $password)

/*  INSERT02
 *  Create a new question
 *  Hundreds per day
 */
INSERT INTO content (main, author_id)
VALUES ($main, $author_id)

INSERT INTO question (content_id, title)
VALUES ($content_id, $title)

/*  INSERT03
 *  Answer a question
 *  Thousands per day
 */
INSERT INTO content (main, author_id)
VALUES ($main, $author_id)

INSERT INTO answer(content_id, question_id)
VALUES ($content_id, $question_id)

/*  INSERT04
 *  Comment on a question
 *  Hundreds per day
 */
INSERT INTO content (main, author_id)
VALUES ($main, $author_id)

INSERT INTO question_comment(content_id, question_id)
VALUES ($content_id, $question_id)

/*  INSERT05
 *  Comment on an answer
 *  Thousands per day
 */
INSERT INTO content (main, author_id)
VALUES ($main, $author_id)

INSERT INTO answer_comment(content_id, answer_id)
VALUES ($content_id, $answer_id)

/*  INSERT06
 *  Vote on a question
 *  Thousands per day
 */
INSERT INTO user_vote_question("user_id", question_id, vote)
VALUES ($user_id, $question_id, $vote)

/*  INSERT07
 *  Vote on an answer
 *  Thousands per day
 */
INSERT INTO user_vote_answer("user_id", answer_id, vote)
VALUES ($user_id, $answer_id, $vote)

/*  INSERT08
 *  Follow a new tag
 *  Hundreds per day
 */
INSERT INTO follow_tag(tag_id, "user_id")
VALUES ($tag_id, $user_id)

/*  INSERT09
 *  New notification
 *  Thousands per day
 */
INSERT INTO notification(type, content, icon, "user_id")
VALUES ($type, $content,$icon, $user_id)

/*  INSERT10
 *  Report a user or content
 *  Hundreds per week
 */
INSERT INTO report(description, reporter_id)
VALUES ($description, $reporter_id)

--- If it's a user ---
INSERT INTO user_report(report_id, "user_id")
VALUES ($report_id, $user_id)

--- If it's content ---
INSERT INTO content_report(report_id, content_id)
VALUES ($report_id, $content_id)

/*  INSERT11
 *  Moderator bans a user
 *  Dozens per week
 */
INSERT INTO ban("end", reason, "user_id", moderator_id)
VALUES ($end, $reason, $user_id, $moderator_id)

/*  INSERT12
 *  Create a new tag
 *  Dozens per week
 */
INSERT INTO tag(name, description)
VALUES ($name, $description)

/*  INSERT13
 *  Save a question
 *  Hundreds per week
 */
INSERT INTO saved_question("user_id", question_id)
VALUES ($user_id, $question_id)

 /*  INSERT14
 *  Add a tag to a question
 *  Thousands per day
 */
INSERT INTO question_tag(question_id, tag_id)
VALUES ($question_id, $tag_id)

