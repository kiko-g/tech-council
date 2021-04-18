/*  DELETE01
 *  Delete a user
 *  Units per day
 */
DELETE FROM "user"
WHERE id = $user_id;

/*  DELETE02
 *  Delete content: question, answer or comment
 *  Dozens per day
 */
DELETE FROM content
WHERE id = $content_id;

/*  DELETE03
 *  Undo vote on question/answer
 *  Dozens per day
 */
DELETE FROM user_vote_question
WHERE question_id = $question_id AND user_id = $user_id;

DELETE FROM user_vote_answer
WHERE answer_id = $answer_id AND user_id = $user_id;

/*  DELETE04
 *  Set notification as seen
 *  Hundreds per day
 */
DELETE FROM "notification"
WHERE id = $notification_id;