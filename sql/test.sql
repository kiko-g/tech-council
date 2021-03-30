INSERT INTO photo (path) VALUES ('photo.png');
INSERT INTO "user" (email, "name", "password", reputation, profile_photo) VALUES ('test', 'misterarnildo', '123', 0, 1);
INSERT INTO "user" (email, "name", "password", reputation, profile_photo) VALUES ('mail', 'user', '123', 3, 1);
INSERT INTO content (id, main, author_id) VALUES (50, 'question', 1);
INSERT INTO content (id, main, author_id) VALUES (51, 'answer', 1);
INSERT INTO question (content_id, title) VALUES (50, 'title');
INSERT INTO answer (content_id, question_id) VALUES (51, 50);

INSERT INTO user_vote_question ("user_id", question_id, upvote) VALUES (1, 50, 1);
INSERT INTO user_vote_question ("user_id", question_id, upvote) VALUES (2, 50, 1);
INSERT INTO user_vote_answer ("user_id", answer_id, upvote) VALUES (1, 51, -1);