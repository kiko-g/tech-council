------------------------------
--TEST UPVOTE RELATED TRIGGERS
------------------------------

INSERT INTO photo (id, path) VALUES (1, 'photo.png');
INSERT INTO "user" (email, "name", "password", reputation, bio, expert, banned, profile_photo) VALUES ('test', 'misterarnildo', '123', 1499, 'wanna be expert', FALSE, FALSE, 1);
INSERT INTO "user" (email, "name", "password", reputation, bio, expert, banned, profile_photo) VALUES ('mail', 'user', '123', 1400, 'im goin down', TRUE, FALSE, 1);
INSERT INTO content (id, main, author_id) VALUES (50, 'question', 1);
INSERT INTO content (id, main, author_id) VALUES (51, 'answer', 2);
INSERT INTO question (content_id, title) VALUES (50, 'title');
INSERT INTO answer (content_id, question_id) VALUES (51, 50);

--test insert operations
INSERT INTO user_vote_question ("user_id", question_id, upvote) VALUES (1, 50, 1);
INSERT INTO user_vote_question ("user_id", question_id, upvote) VALUES (2, 50, 1);
INSERT INTO user_vote_answer ("user_id", answer_id, upvote) VALUES (1, 51, -1);
SELECT * FROM "user"; --user 1 rep has to be 1501 and is now expert, user 2 rep has to be 1399 and is not expert
SELECT * FROM question; --question 50 rep has to be 2
SELECT * FROM answer; --answer 51 rep has to be -1

--test update operations
UPDATE user_vote_question SET upvote = -1 WHERE "user_id" = 1;
UPDATE user_vote_answer SET upvote = 1 WHERE "user_id" = 1;
SELECT * FROM "user"; --user 1 rep has to be 1499, user 2 rep has to be 1401
SELECT * FROM question; --question 50 rep has to be 0
SELECT * FROM answer; --answer 51 rep has to be 1

--test delete operations
DELETE FROM user_vote_question WHERE "user_id" = 1;
DELETE FROM user_vote_answer WHERE "user_id" = 1;
SELECT * FROM "user"; --user 1 rep has to be 1500, user 2 rep has to be 1400
SELECT * FROM question; --question 50 rep has to be 1
SELECT * FROM answer; --answer 51 rep has to be 0

------------------------------
--TEST BAN RELATED TRIGGERS
------------------------------

INSERT INTO photo (id, path) VALUES (1, 'photo.png');
INSERT INTO "user" (id, email, "name", "password", reputation, bio, expert, banned, profile_photo) VALUES (1, 'banmail', 'ban', '123', 1400, 'im goin ban', FALSE, FALSE, 1);
INSERT INTO "user" (id, email, "name", "password", reputation, bio, expert, banned, profile_photo) VALUES (2, 'modmail', 'mod', '123', 7000, 'its ban time', TRUE, FALSE, 1);
INSERT INTO moderator ("user_id") VALUES (2);
INSERT INTO report (id, "description", reporter_id) VALUES (30, 'testing report', 1);

-- moderator solved reports count
UPDATE report SET solved = TRUE, solver_id = 2 WHERE id = 30;
SELECT * FROM moderator; -- should have one solved report

-- update user ban status and moderator user ban count
INSERT INTO ban ("end", reason, "user_id", moderator_id) VALUES ('2030-01-08', 'not good', 1, 2);
SELECT * FROM "user"; --user 1 should be banned
SELECT * FROM moderator; --moderator should have banned one user

-- self report
INSERT INTO report (id, "description", reporter_id) VALUES (31, 'self report', 1);
INSERT INTO user_report (report_id, "user_id") VALUES (31, 1);
INSERT INTO report (id, "description", reporter_id) VALUES (34, 'self report', 1);
INSERT INTO user_report (report_id, "user_id") VALUES (34, 2);
SELECT * FROM report; -- should not have report with id 31 and have 34
SELECT * FROM user_report; -- should not have user report with id 31 and have 34

-- self content report
INSERT INTO content (id, main, author_id) VALUES (52, 'question', 1);
INSERT INTO question (content_id, title) VALUES (52, 'title');
INSERT INTO report (id, "description", reporter_id) VALUES (32, 'self report', 1);
INSERT INTO content_report (report_id, content_id) VALUES (32, 52);
INSERT INTO report (id, "description", reporter_id) VALUES (33, 'not self report', 2);
INSERT INTO content_report (report_id, content_id) VALUES (33, 52);
SELECT * FROM report; -- should not have report with id 32 and have report 33
SELECT * FROM content_report; -- should not have user report with id 32 and have report 33

------------------------------
--TEST NOTIFICATIONS
------------------------------
INSERT INTO photo (id, path) VALUES (1, 'photo.png');
INSERT INTO "user" (email, "name", "password", reputation, bio, expert, banned, profile_photo) VALUES ('test', 'misterarnildo', '123', 1499, 'wanna be expert', FALSE, FALSE, 1);
INSERT INTO "user" (email, "name", "password", reputation, bio, expert, banned, profile_photo) VALUES ('mail', 'user', '123', 1400, 'im goin down', TRUE, FALSE, 1);
INSERT INTO content (id, main, author_id) VALUES (50, 'question', 1);
INSERT INTO question (content_id, title) VALUES (50, 'title');

-- test answered notification
INSERT INTO content (id, main, author_id) VALUES (51, 'answer', 2);
INSERT INTO answer (content_id, question_id) VALUES (51, 50);
SELECT * FROM "notification"; --user 1 should have one notification of type answered

-- test answered saved question notification
INSERT INTO saved_question("user_id", question_id) VALUES (2, 50);
INSERT INTO "user" (email, "name", "password", reputation, bio, expert, banned, profile_photo) VALUES ('m', 'user', '123', 0, 'idk', TRUE, FALSE, 1);
INSERT INTO saved_question("user_id", question_id) VALUES (3, 50);
INSERT INTO content (id, main, author_id) VALUES (52, 'answer', 1);
INSERT INTO answer (content_id, question_id) VALUES (52, 50);
SELECT * FROM "notification"; --user 2 and 3 should have a answered_saved notification