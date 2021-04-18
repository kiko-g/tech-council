/*  TRANSACTION01
 *  Create a new Question, Answer or Comment
 *  A transaction is necessary to make sure that both the content table and the question/answer/comment table 
 is populated without errors. If an error occurs, such as failing to insert new content, a ROLLBACK is issued.
 To make sure that the id of the content table is not updated, the isolation level is REPEATABLE READ. There
 are 4 transactions, one for each type of content: question, answer, comment to a question and comment to an
 answer. Furthermore, there are 5 versions of this transaction for the question variant, given that it can have 
 between 1 and 5 tags, but for the sake of simplicity, only the 1 tag version is included here.
 *  Repeatable Read
 */
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ 

-- Insert content
INSERT INTO content (main, author_id)
VALUES ($body, $user_id);

-- Insert question
INSERT INTO question (content_id, title)
VALUES (currval("content_id_seq"), $title);

-- Insert a tag for the question
INSERT INTO question_tag (question_id, tag_id)
VALUES (currval("content_id_seq"), $tag_id);

COMMIT;

-----------------------------------------------------------

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ 

-- Insert content
INSERT INTO content (main, author_id)
VALUES ($body, $user_id);

-- Insert answer
INSERT INTO answer (content_id, question_id)
VALUES (currval("content_id_seq"), $question_id);

COMMIT;

-----------------------------------------------------------

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ 

-- Insert content
INSERT INTO content (main, author_id)
VALUES ($body, $user_id);

-- Insert comment to a question
INSERT INTO question_comment (content_id, question_id)
VALUES (currval("content_id_seq"), $question_id);

COMMIT;

-----------------------------------------------------------

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ 

-- Insert content
INSERT INTO content (main, author_id)
VALUES ($body, $user_id);

-- Insert comment to a question
INSERT INTO answer_comment (content_id, answer_id)
VALUES (currval("content_id_seq"), $answer_id);

COMMIT;



/*  TRANSACTION02
 *  Report a user or any type of content
 *  A transaction is necessary to make sure that both the report table and the user_report/content_report tables 
 are populated without errors. If an error occurs, such as failing to insert a new report, a ROLLBACK is issued.
 To make sure that the id of the report table is not updated, the isolation level is REPEATABLE READ. There
 are 2 transactions, one for each type of report: user report or content report.
 *  Repeatable Read
 */

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ 

-- Insert report
INSERT INTO report (description, reporter_id)
VALUES ($description, $user_id);

-- Insert reported user 
INSERT INTO user_report (report_id, user_id)
VALUES (currval('report_id_seq'), $user_id);

COMMIT;

-----------------------------------------------------------

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ 

-- Insert report
INSERT INTO report (description, reporter_id)
VALUES ($description, $user_id);

-- Insert reported content 
INSERT INTO content_report (report_id, content_id)
VALUES (currval('report_id_seq'), $content_id);

COMMIT;



/*  TRANSACTION03
 *  Edit a question
 *  When editing a question, its title and main content are stored in separate tables. To make sure that both
 sides are updated sequentially and error-free, a transaction is used. The isolation level is READ UNCOMMITED,
 given that there are no risks of dirty reads, phantom reads and non-repeatable reads.
 *  Read Uncommited
 */
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL READ UNCOMMITED 

-- Update question's content content
UPDATE content SET main = $main
WHERE id = $content_id;

-- Update question's title 
UPDATE post SET title = $title
WHERE content_id = $content_id;

COMMIT;