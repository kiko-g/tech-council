/*  UPDATE01
 *  Update user info
 *  Dozens per day
 */
UPDATE "user"
SET email = $email, name = $name, password = $password, bio = $bio, profile_photo = $photo
WHERE id = $user_id;

/*  UPDATE02
 *  Update content of a question (except title), answer or comment
 *  Dozens per day
 */
UPDATE content
SET main = $main, modification_date = NOW(), edited = TRUE
WHERE id = $content_id;

 /*  UPDATE03
 *  Update a question's title
 *  Dozens per day
 */
UPDATE question
SET title = $title
WHERE id = $content_id;

UPDATE content
SET modification_date = NOW(), edited = TRUE
WHERE id = $content_id;

/*  UPDATE04
 *  Ban or unban a user
 *  Dozens per day
 */
UPDATE "user"
SET banned = TRUE
WHERE id = $user_id

UPDATE "user"
SET banned = FALSE
WHERE id = $user_id

/*  UPDATE05
 *  Update vote for a question/answer
 *  Hundreds per day
 */
UPDATE user_vote_question
SET vote = $vote
WHERE question_id = $question_id AND user_id = $user_id;

UPDATE user_vote_answer
SET vote = $vote
WHERE answer_id = $answer_id AND user_id = $user_id;

/*  UPDATE06
 *  Update moderator stats
 *  Dozens per day
 */
UPDATE moderator
SET questions_deleted = $questions_deleted, answers_deleted = $answers_deleted, comments_deleted = $comments_deleted, banned_users = $banned_users, solved_reports = $solved_reports 
WHERE "user_id" = $user_id;

/*  UPDATE07
 *  Solve a report
 *  Dozens per day
 */
UPDATE report
SET solved = TRUE, solver_id = $solver_id
WHERE id = $report_id;
