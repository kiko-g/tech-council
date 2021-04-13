--TEST UPVOTE RELATED TRIGGERS
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