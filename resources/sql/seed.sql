---------------------
--- Cleanse database
---------------------

DROP TABLE IF EXISTS content CASCADE;
DROP TABLE IF EXISTS question CASCADE;
DROP TABLE IF EXISTS answer CASCADE;
DROP TABLE IF EXISTS answer_comment CASCADE;
DROP TABLE IF EXISTS question_comment CASCADE;
DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS moderator CASCADE;
DROP TABLE IF EXISTS tag CASCADE;
DROP TABLE IF EXISTS photo CASCADE;
DROP TABLE IF EXISTS "notification" CASCADE;
DROP TABLE IF EXISTS ban CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS user_report CASCADE;
DROP TABLE IF EXISTS content_report CASCADE;
DROP TABLE IF EXISTS follow_tag CASCADE;
DROP TABLE IF EXISTS user_vote_question CASCADE;
DROP TABLE IF EXISTS user_vote_answer CASCADE;
DROP TABLE IF EXISTS saved_question CASCADE;
DROP TABLE IF EXISTS question_tag CASCADE;

------------------
-- Create types
------------------

DROP TYPE IF EXISTS "NOTIFICATION";
CREATE TYPE "NOTIFICATION" AS ENUM ('answered', 'answered_saved', 'upvote_question', 'upvote_answer', 'best_answer');

------------------
-- Create tables
------------------

CREATE TABLE photo (
    id SERIAL PRIMARY KEY,
    path TEXT NOT NULL UNIQUE -- path might be a keyword
);

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    email TEXT NOT NULL UNIQUE,
    "name" TEXT NOT NULL UNIQUE,
    "password" TEXT NOT NULL,
    join_date DATE NOT NULL DEFAULT NOW(),
    reputation INTEGER NOT NULL DEFAULT 0,
    bio TEXT,
    expert BOOLEAN NOT NULL DEFAULT FALSE,
    banned BOOLEAN NOT NULL DEFAULT FALSE,
    search tsvector,
    profile_photo INTEGER REFERENCES photo(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE tag (
    id SERIAL PRIMARY KEY,
    "name" TEXT UNIQUE NOT NULL,
    "description" TEXT NOT NULL,
    search tsvector,
    author_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE content (
    id SERIAL PRIMARY KEY,
    main TEXT NOT NULL,
    creation_date DATE NOT NULL DEFAULT NOW(),
    modification_date DATE DEFAULT NULL,
    author_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL,
    edited BOOLEAN NOT NULL DEFAULT FALSE,
    search tsvector,
    CONSTRAINT mod_after_cre CHECK(modification_date >= creation_date)
);

CREATE TABLE question (
    content_id INTEGER PRIMARY KEY REFERENCES content(id) ON UPDATE CASCADE ON DELETE CASCADE,
    title TEXT NOT NULL,
    votes_difference INTEGER NOT NULL DEFAULT 0,
    search tsvector
);

CREATE TABLE answer (
    content_id INTEGER PRIMARY KEY REFERENCES content(id) ON UPDATE CASCADE ON DELETE CASCADE,
    votes_difference INTEGER NOT NULL DEFAULT 0,
    question_id INTEGER REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    is_best_answer BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE answer_comment (
    content_id INTEGER PRIMARY KEY REFERENCES content(id) ON UPDATE CASCADE ON DELETE CASCADE,
    answer_id INTEGER REFERENCES answer(content_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE question_comment (
    content_id INTEGER PRIMARY KEY REFERENCES content(id) ON UPDATE CASCADE ON DELETE CASCADE,
    question_id INTEGER REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE moderator (
    "user_id" INTEGER PRIMARY KEY REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    questions_deleted INTEGER NOT NULL DEFAULT 0,
    answers_deleted INTEGER NOT NULL DEFAULT 0,
    comments_deleted INTEGER NOT NULL DEFAULT 0,
    banned_users INTEGER NOT NULL DEFAULT 0,
    solved_reports INTEGER NOT NULL DEFAULT 0,
    CONSTRAINT non_negative_questions_deleted CHECK (questions_deleted >= 0),
    CONSTRAINT non_negative_answers_deleted CHECK (answers_deleted >= 0),
    CONSTRAINT non_negative_comments_deleted CHECK (comments_deleted >= 0),
    CONSTRAINT non_negative_banned_users CHECK (banned_users >= 0),
    CONSTRAINT non_negative_solved_reports CHECK (solved_reports >= 0)
);

CREATE TABLE "notification" (
    id SERIAL PRIMARY KEY,
    "type" "NOTIFICATION" NOT NULL,
    content TEXT NOT NULL,
    icon TEXT NOT NULL,
    "date" DATE NOT NULL DEFAULT NOW(),
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT date_before_today CHECK ("date" <= now())
);

CREATE TABLE ban (
    id SERIAL PRIMARY KEY,
    "start" DATE NOT NULL DEFAULT NOW(),
    "end" DATE,
    reason TEXT NOT NULL,
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    moderator_id INTEGER REFERENCES moderator("user_id") ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT start_before_today CHECK ("start" <= now()),
    CONSTRAINT valid_ban CHECK ("end" > "start")
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    "description" TEXT NOT NULL,
    solved BOOLEAN NOT NULL DEFAULT FALSE,
    reporter_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    solver_id INTEGER DEFAULT NULL REFERENCES moderator("user_id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE user_report (
    report_id INTEGER PRIMARY KEY REFERENCES report(id) ON UPDATE CASCADE ON DELETE CASCADE,
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL    
);

CREATE TABLE content_report (
    report_id INTEGER PRIMARY KEY REFERENCES report(id) ON UPDATE CASCADE ON DELETE CASCADE,
    content_id INTEGER NOT NULL REFERENCES content(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE follow_tag (
    id SERIAL PRIMARY KEY,
    tag_id INTEGER NOT NULL REFERENCES tag(id) ON UPDATE CASCADE ON DELETE CASCADE,
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE (tag_id, "user_id")
);

CREATE TABLE user_vote_question (
    id SERIAL PRIMARY KEY,
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    question_id INTEGER NOT NULL REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    vote INTEGER NOT NULL,
    UNIQUE ("user_id", question_id)
);

CREATE TABLE user_vote_answer (
    id SERIAL PRIMARY KEY,
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    answer_id INTEGER NOT NULL REFERENCES answer(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    vote INTEGER NOT NULL,
    UNIQUE ("user_id", answer_id)
);

CREATE TABLE saved_question (
    id SERIAL PRIMARY KEY,
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    question_id INTEGER NOT NULL REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE ("user_id", question_id)
);

CREATE TABLE question_tag (
    id SERIAL PRIMARY KEY,
    question_id INTEGER NOT NULL REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    tag_id INTEGER NOT NULL REFERENCES tag(id) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE (question_id, tag_id)
);

------------------
-- Indexes
------------------

-- Performance
CREATE INDEX content_author ON content USING hash (author_id);
CREATE INDEX content_dates ON content USING btree (creation_date);
CREATE INDEX notification_date ON notification USING btree (date);
CREATE INDEX notification_user_id ON notification USING btree (user_id);

-- FTS
CREATE INDEX question_search ON question USING GIST (search); 
CREATE INDEX tag_search ON tag USING GIN (search);
CREATE INDEX user_search ON "user" USING GIST (search);
CREATE INDEX content_search ON content USING GIST (search);


------------------
-- Triggers
------------------

--------------------------------------
-- UPDATE USER AND QUESTION REPUTATION 
--------------------------------------

-- on insert to question table
DROP FUNCTION IF EXISTS insert_reputation_question CASCADE;
CREATE FUNCTION insert_reputation_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.vote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id", icon) VALUES ('upvote_question', 'Your question has been upvoted!',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.question_id)), 'photo.png');
    END IF;

    -- update user reputation
    UPDATE "user" SET reputation = reputation + NEW.vote
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.question_id));

    --verify if user is now expert
    UPDATE "user" SET expert = TRUE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.question_id))
        AND "user".expert = FALSE
        AND "user".reputation >= 1500;

    --verify if user has lost expert status
    UPDATE "user" SET expert =  FALSE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.question_id))
        AND "user".expert = TRUE
        AND "user".reputation < 1400;

    --update question votes difference
    UPDATE question SET votes_difference = votes_difference + NEW.vote
    WHERE content_id = NEW.question_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS insert_reputation_question ON user_vote_question;
CREATE TRIGGER insert_reputation_question
    AFTER INSERT ON user_vote_question
    FOR EACH ROW
    EXECUTE PROCEDURE insert_reputation_question();

-- on insert to answer table
DROP FUNCTION IF EXISTS insert_reputation_answer CASCADE;
CREATE FUNCTION insert_reputation_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.vote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id", icon) VALUES ('upvote_question', 'upvote question notify',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.answer_id)), 'photo.png');
    END IF;

    -- update user reputation
    UPDATE "user" SET reputation = reputation + NEW.vote
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.answer_id));

    --verify if user is now expert
    UPDATE "user" SET expert = TRUE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.answer_id))
        AND "user".expert = FALSE
        AND "user".reputation >= 1500;

    --verify if user has lost expert status
    UPDATE "user" SET expert =  FALSE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.answer_id))
        AND "user".expert = TRUE
        AND "user".reputation < 1400;

    --update answer votes difference
    UPDATE answer SET votes_difference = votes_difference + NEW.vote
    WHERE content_id = NEW.answer_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS insert_reputation_answer ON user_vote_answer;
CREATE TRIGGER insert_reputation_answer
    AFTER INSERT ON user_vote_answer
    FOR EACH ROW
    EXECUTE PROCEDURE insert_reputation_answer();

-- on update to question table
DROP FUNCTION IF EXISTS update_reputation_question CASCADE;
CREATE FUNCTION update_reputation_question() RETURNS TRIGGER AS
$BODY$
DECLARE rep integer := NEW.vote - OLD.vote;
BEGIN
    IF NEW.vote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id", icon) VALUES ('upvote_question', 'Your question has been upvoted!',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.question_id)), 'photo.png');
    END IF;

    -- update user reputation
    UPDATE "user" SET reputation = reputation + rep
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.question_id));

    --verify if user is now expert
    UPDATE "user" SET expert = TRUE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.question_id))
        AND "user".expert = FALSE
        AND "user".reputation >= 1500;

    --verify if user has lost expert status
    UPDATE "user" SET expert =  FALSE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.question_id))
        AND "user".expert = TRUE
        AND "user".reputation < 1400;

    --update question votes difference
    UPDATE question SET votes_difference = votes_difference + rep
    WHERE content_id = NEW.question_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_reputation_question ON user_vote_question;
CREATE TRIGGER update_reputation_question
    AFTER UPDATE ON user_vote_question
    FOR EACH ROW
    EXECUTE PROCEDURE update_reputation_question();

-- on update to answer table
DROP FUNCTION IF EXISTS update_reputation_answer CASCADE;
CREATE FUNCTION update_reputation_answer() RETURNS TRIGGER AS
$BODY$
DECLARE rep integer := NEW.vote - OLD.vote;
BEGIN
    IF NEW.vote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id", icon) VALUES ('upvote_question', 'Your question has been upvoted!',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.answer_id)), 'photo.png');
    END IF;

    -- update user reputation
    UPDATE "user" SET reputation = reputation + rep
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.answer_id));

    --verify if user is now expert
    UPDATE "user" SET expert = TRUE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.answer_id))
        AND "user".expert = FALSE
        AND "user".reputation >= 1500;

    --verify if user has lost expert status
    UPDATE "user" SET expert =  FALSE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = NEW.answer_id))
        AND "user".expert = TRUE
        AND "user".reputation < 1400;

    --update answer votes difference
    UPDATE answer SET votes_difference = votes_difference + rep
    WHERE content_id = NEW.answer_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_reputation_answer ON user_vote_answer;
CREATE TRIGGER update_reputation_answer
    AFTER UPDATE ON user_vote_answer
    FOR EACH ROW
    EXECUTE PROCEDURE update_reputation_answer();

-- on delete from question table
DROP FUNCTION IF EXISTS delete_reputation_question CASCADE;
CREATE FUNCTION delete_reputation_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    -- update user reputation
    UPDATE "user" SET reputation = reputation - OLD.vote
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = OLD.question_id));

    --verify if user is now expert
    UPDATE "user" SET expert = TRUE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = OLD.question_id))
        AND "user".expert = FALSE
        AND "user".reputation >= 1500;

    --verify if user has lost expert status
    UPDATE "user" SET expert =  FALSE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = OLD.question_id))
        AND "user".expert = TRUE
        AND "user".reputation < 1400;

    --update question votes difference
    UPDATE question SET votes_difference = votes_difference - OLD.vote
    WHERE content_id = OLD.question_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
DROP TRIGGER IF EXISTS delete_reputation_question ON user_vote_question;
CREATE TRIGGER delete_reputation_question
    AFTER DELETE ON user_vote_question
    FOR EACH ROW
    EXECUTE PROCEDURE delete_reputation_question();

-- on delete from answer table
DROP FUNCTION IF EXISTS delete_reputation_answer CASCADE;
CREATE FUNCTION delete_reputation_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    -- update user reputation
    UPDATE "user" SET reputation = reputation - OLD.vote
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = OLD.answer_id));

    --verify if user is now expert
    UPDATE "user" SET expert = TRUE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = OLD.answer_id))
        AND "user".expert = FALSE
        AND "user".reputation >= 1500;

    --verify if user has lost expert status
    UPDATE "user" SET expert =  FALSE
    WHERE "user".id = (SELECT author_id FROM content 
                        WHERE (content.id = OLD.answer_id))
        AND "user".expert = TRUE
        AND "user".reputation < 1400;

    --update answer votes difference
    UPDATE answer SET votes_difference = votes_difference - OLD.vote
    WHERE content_id = OLD.answer_id;

    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;
 
DROP TRIGGER IF EXISTS delete_reputation_answer ON user_vote_answer;
CREATE TRIGGER delete_reputation_answer
    AFTER DELETE ON user_vote_answer
    FOR EACH ROW
    EXECUTE PROCEDURE delete_reputation_answer();

--------------------------------------
-- UPDATE USER AND MODERATOR BAN INFORMATION 
--------------------------------------

-- update moderator solved reports
DROP FUNCTION IF EXISTS solved_report CASCADE;
CREATE FUNCTION solved_report() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE moderator SET solved_reports = solved_reports + 1
    WHERE "user_id" = NEW.solver_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS solved_report ON report;
CREATE TRIGGER solved_report
    AFTER UPDATE ON report
    FOR EACH ROW
    EXECUTE PROCEDURE solved_report();

-- creation of a ban entry
DROP FUNCTION IF EXISTS insert_ban CASCADE;
CREATE FUNCTION insert_ban() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE moderator SET banned_users = banned_users + 1
    WHERE moderator."user_id" = NEW.moderator_id;

    UPDATE "user" SET banned = TRUE
    WHERE "user".id = NEW."user_id";

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS insert_ban ON ban;
CREATE TRIGGER insert_ban
    AFTER INSERT ON ban
    FOR EACH ROW
    EXECUTE PROCEDURE insert_ban();

-- user cannot report himself
DROP FUNCTION IF EXISTS no_self_report CASCADE;
CREATE FUNCTION no_self_report() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT * FROM report 
        WHERE id = NEW.report_id AND reporter_id = NEW."user_id"
    ) THEN
        RAISE EXCEPTION 'User cannot report himself!';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS no_self_report ON user_report;
CREATE TRIGGER no_self_report
    BEFORE INSERT ON user_report
    FOR EACH ROW
    EXECUTE PROCEDURE no_self_report();

-- user cannot report his own content
DROP FUNCTION IF EXISTS no_self_content_report CASCADE;
CREATE FUNCTION no_self_content_report() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT * FROM report WHERE id = NEW.report_id AND reporter_id = (
            SELECT author_id FROM content WHERE id = NEW.content_id
        )
    ) THEN
        RAISE EXCEPTION 'User cannot report his own content!';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS no_self_content_report ON content_report;
CREATE TRIGGER no_self_content_report
    BEFORE INSERT ON content_report
    FOR EACH ROW
    EXECUTE PROCEDURE no_self_content_report();

-- user cannot report moderators
DROP FUNCTION IF EXISTS no_moderator_report CASCADE;
CREATE FUNCTION no_moderator_report() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT * FROM moderator WHERE moderator."user_id" = NEW."user_id"
    ) THEN
        RAISE EXCEPTION 'Moderators cannot be reported';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS no_moderator_report ON user_report;
CREATE TRIGGER no_moderator_report
    BEFORE INSERT ON user_report
    FOR EACH ROW
    EXECUTE PROCEDURE no_moderator_report();

-- user cannot report moderators' content
DROP FUNCTION IF EXISTS no_moderator_content_report CASCADE;
CREATE FUNCTION no_moderator_content_report() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT * FROM moderator WHERE "user_id" = (
            SELECT author_id FROM content WHERE id = NEW.content_id
        )
    ) THEN
        RAISE EXCEPTION 'Moderators content cannot be reported';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS no_moderator_content_report ON content_report;
CREATE TRIGGER no_moderator_content_report
    BEFORE INSERT ON content_report
    FOR EACH ROW
    EXECUTE PROCEDURE no_moderator_content_report();

--------------------------------------
-- NOTIFICATION RELATED TRIGGERS 
--------------------------------------

-- answer to user question
DROP FUNCTION IF EXISTS notify_answer CASCADE;
CREATE FUNCTION notify_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO "notification" ("type", content, "user_id", icon) VALUES ('answered', 'Someone answered your question!',
        (SELECT author_id FROM content 
            WHERE (content.id = NEW.question_id)), 'photo.png');

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS notify_answer ON answer;
CREATE TRIGGER notify_answer
    AFTER INSERT ON answer
    FOR EACH ROW
    EXECUTE PROCEDURE notify_answer();

-- answer to saved question
DROP FUNCTION IF EXISTS notify_saved_question CASCADE;
CREATE FUNCTION notify_saved_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO "notification" ("type", content, "user_id", icon) 
        SELECT 'answered_saved', 'Someone answered a question you saved!', "user_id", 'photo.png'
        FROM saved_question AS saved 
        WHERE saved.question_id = NEW.question_id;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS notify_saved_question ON answer;
CREATE TRIGGER notify_saved_question
    AFTER INSERT ON answer
    FOR EACH ROW
    EXECUTE PROCEDURE notify_saved_question();

-- answer voted has best answer
DROP FUNCTION IF EXISTS notify_best_answer CASCADE;
CREATE FUNCTION notify_best_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF ((SELECT author_id FROM content 
                WHERE content.id = NEW.question_id) <> 
        (SELECT author_id FROM content 
                WHERE content.id = NEW.content_id)) AND
        NEW.is_best_answer = TRUE
    THEN
        -- verify if there is already a best answer
        IF EXISTS (
            SELECT * FROM answer 
            WHERE answer.content_id <> NEW.content_id AND
                answer.question_id = NEW.question_id AND
                answer.is_best_answer = TRUE
        )
        THEN
            RAISE EXCEPTION 'There is already a best answer for question';
        END IF;

        -- update user reputation
        UPDATE "user" SET reputation = reputation + 20
        WHERE "user".id = (SELECT author_id FROM content 
                            WHERE (content.id = NEW.content_id));

        -- create notification
        INSERT INTO "notification" ("type", content, "user_id", icon) 
        VALUES ('best_answer', 'Your answer has been selected has the best one!', (
            SELECT author_id FROM content 
                WHERE (content.id = NEW.content_id) 
        ), 'photo.png');
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS notify_best_answer ON answer;
CREATE TRIGGER notify_best_answer
    BEFORE UPDATE ON answer
    FOR EACH ROW
    EXECUTE PROCEDURE notify_best_answer();

--------------------------------------
-- TAG RELATED TRIGGERS 
--------------------------------------

-- not allow regular users to create tags
DROP FUNCTION IF EXISTS create_tag CASCADE;
CREATE FUNCTION create_tag() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (
    NOT EXISTS (
        SELECT * FROM moderator 
        WHERE moderator."user_id" = NEW.author_id
    ) AND (
        NOT (SELECT expert FROM "user" WHERE "user".id = NEW.author_id)
    ))
    THEN RAISE EXCEPTION 'Only moderators and expert users can create tags!';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS create_tag ON answer;
CREATE TRIGGER create_tag
    BEFORE INSERT ON tag
    FOR EACH ROW
    EXECUTE PROCEDURE create_tag();

--------------------------------------
-- POPULATE DATABASE
--------------------------------------

-- PHOTO TABLE
INSERT INTO photo(path) VALUES ('assets/photos/user-default.png');
INSERT INTO photo(path) VALUES ('assets/photos/user1.png');
INSERT INTO photo(path) VALUES ('assets/photos/user2.png');
INSERT INTO photo(path) VALUES ('assets/photos/user3.png');
INSERT INTO photo(path) VALUES ('assets/photos/user4.png');
INSERT INTO photo(path) VALUES ('assets/photos/user5.png');
INSERT INTO photo(path) VALUES ('assets/photos/user6.png');
INSERT INTO photo(path) VALUES ('assets/photos/user7.png');
INSERT INTO photo(path) VALUES ('assets/photos/user8.png');
INSERT INTO photo(path) VALUES ('assets/photos/user9.png');
INSERT INTO photo(path) VALUES ('assets/photos/user10.png');
INSERT INTO photo(path) VALUES ('assets/photos/user11.png');
INSERT INTO photo(path) VALUES ('assets/photos/user12.png');
INSERT INTO photo(path) VALUES ('assets/photos/user13.png');
INSERT INTO photo(path) VALUES ('assets/photos/user14.png');
INSERT INTO photo(path) VALUES ('assets/photos/user15.png');
INSERT INTO photo(path) VALUES ('assets/photos/user16.png');
INSERT INTO photo(path) VALUES ('assets/photos/user17.png');
INSERT INTO photo(path) VALUES ('assets/photos/user18.png');
INSERT INTO photo(path) VALUES ('assets/photos/user19.png');
INSERT INTO photo(path) VALUES ('assets/photos/user20.png');



-- USER TABLE
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('francisco.friande@fe.up.pt','FFiambre','$2b$12$GScCSgD/1ExItak4E2F1Meogf9kjG0/6fjdN3gmAcjvpPTuQt/ALG','3/14/2021','2000','My name is Francisco Friande, I am 23 years old and I live in Bắc Giang, Vietnam. I''m interested in Photos and Videos','False','True',1);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('francisco.jpg@fe.up.pt','KikoDeLasAlves','$2b$12$X2U..hrlhM.UyKc29snBRePQffbvKC8ODqVG2Oxs6MRS1I7QJg50a','3/13/2021','2000','My name is Francisco Gonçalves, I am 21 years old and I live in Porto, Portugal. I''m interested in Android, Keras, Data and JavaScript','False','True',2);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('joao.romao@fe.up.pt','jdiogueiro','$2b$12$VdKvH5NmBz9EJkS6wut3BeD00xn3o4MfI2h0Nwtg/Rxtb90gWIiZW','11/23/2020','2000','My name is João Romão, I am 20 years old and I live in Raghunathpur, India. I''m interested in PyCharm, Eclipse and Mac','False','True',3);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('miguel.pinto@fe.up.pt','mpintarolas','$2b$12$V41At4HQkZBMoc.SED.lhOz1n5IwT4BbWQVu7YVY1FSFw6yHybeIy','10/27/2020','2000','My name is Miguel Pinto, I am 22 years old and I live in Moscow, Russia. I''m interested in Keyboards, Linux and Mac','False','True',4);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('cmoore4@amazonaws.com','jlopes4ever','$2b$12$WrYfJuBXPI/ADECB/NL2WuguSuJQzuBMWnWXRVbHB4jg5brUsMsFS','5/27/2020','45','My name is Cross Moore, I am 31 years old and I live in Kundiān, Pakistan. I''m interested in CMD, Powershell, Linux and Angular','False','False',5);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('lwooder5@msu.edu','laz_woody','$2b$12$xaxNMRv/ujLVHu1jOh.rp.oSdzSh.c3u1VtwRA/sD6Pow3MVjBu4O','12/23/2020','63','My name is Lazarus Wooder, I am 25 years old and I live in Paradise, Trinidad and Tobago. I''m interested in XML, Excel, Security and Xiamoi','False','False',6);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('lbrashier6@eepurl.com','LuceB3','$2b$12$fh5rG9s2pQON35xRZAGh.OH4bcdJ7NDbcadB9FSrLoeiJCm3Ga9ym','2021-09-02 00:00:00','182','My name is Luce Brashier, I am 18 years old and I live in Nagqu, China. I''m interested in Photo Editing, DOM, CMD and Regex','False','False',7);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('bfolshom7@squarespace.com','john_cleeeese','$2b$12$/t5UXW4bGgUUtGJOUBhDfeO.DJeO21fiNlqOhrdN3HRsKi6xWbRDa','2020-05-07 00:00:00','1600','My name is Bess Folshom, I am 57 years old and I live in Dobryanka, Russia. I''m interested in Eclipse and HP','True','True',8);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('srugg8@bigcartel.com','ShaylynnR','$2b$12$lAoe3xI/.Ak9MQAqVYy./OR/oLJ88OQ0I1QabtCQfBgYRGGzY.WqG','2020-12-05 00:00:00','11','My name is Shaylynn Rugg, I am 32 years old and I live in Västervik, Sweden. I''m interested in XML and Cloud','False','False',9);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('byates9@blogs.com','LilYatty','$2b$12$cwsxBN2meq1wxNQd0A9XdOLgkRMvErn3OoS2uM4ROGRg8IzhBVggG','11/30/2020','0','My name is Babita Yates, I am 25 years old and I live in Chilakalūrupet, India. I''m interested in Web Applications, Algorithms, Circle CI and PlayStation','True','False',10);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('fcastagnea@berkeley.edu','floribella69','$2b$12$aWkHeUOcM/iiwNJq5ClE9eIpZuarJ/GChGsVWqi4aToNKwejabTjG','11/28/2020','1700','My name is Florella Castagne, I am 34 years old and I live in Hanawa, Japan. I''m interested in Keyboard and Samsung','False','True',11);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('bclaringboldb@utexas.edu','bugsboony12','$2b$12$sOwZsyfOsD7UW8qeSNMabO6l0Tnv.dRQZESAx2nzK634ajW3oHWlu','9/23/2020','80','My name is Boony Claringbold, I am 57 years old and I live in ’Aïn el Turk, Algeria. I''m interested in Google Drive and PHP','False','False',12);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('hbetancourtc@altervista.org','hakeeeeeeem123','$2b$12$BNc5pj4IOe2uHvRjTRhb9uT9ImJIiP0ACA5qeMD0177qcENNHs88C','2020-10-08 00:00:00','1930','My name is Hakeem Betancourt, I am 43 years old and I live in Forlì, Italy. I''m interested in Mouse','False','True',13);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('sfitterd@instagram.com','the_office_lover','$2b$12$i4vHF5evrdsaLAn6ZctUKu/XgvDKAeiqPOqfoUBFbAPih/iNvDyNa','7/17/2020','22','My name is Stanleigh Fitter, I am 47 years old and I live in Oria, Spain. I''m interested in PHP','False','False',14);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('alikelye@goo.gl','fireToTheRain','$2b$12$coNZ/fmhmUWY2wJ.kPqveu2N8IBSVT0nRhnaHLBt9IZEeSMHJ/9CC','2021-07-02 00:00:00','1860','My name is Adelbert Likely, I am 52 years old and I live in Harburg, Germany. I''m interested in Web Scraping, Apache and XML','False','True',15);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('oapperleyf@msu.edu','olive_oil','$2b$12$F4sqmSZ9y67Vsz4cCoIHcOtIumLaFWu4c.57iwNlsewYtnhrjIoXe','2020-01-08 00:00:00','4','My name is Olivier Apperley, I am 59 years old and I live in Kōnosu, Japan. I''m interested in Cloud and SQL','False','False',16);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('icasierg@cnbc.com','CaseyNeistat','$2b$12$uWQKEnwmK6dpQZjTWdnemeSdJQPi8NUq82EEQnWxJNQSGuOEt96I2','6/26/2020','2100','My name is Inez Casier, I am 21 years old and I live in Burg Unter-Falkenstein, Germany. I''m interested in Eclipse','True','True',17);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('cpatshullh@sciencedaily.com','Patcholi','$2b$12$gUU/uriVzJIkUJWochNxhOdOm.oyKOgBZdV8yUu7t2GB0uhGSCybK','2021-12-03 00:00:00','31','My name is Chrysa Patshull, I am 19 years old and I live in Nabatîyé et Tahta, Lebanon. I''m interested in Oracle and C#','False','False',18);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('cgasperoi@ehow.com','GassyCassy','$2b$12$tXfEEmlndfauQ03Rdt.zE.w9sy6MxA6i54zmnBoRLbUh9C2zAvHvm','2021-12-03 00:00:00','31','My name is Casandra Gaspero, I am 44 years old and I live in Shāhpura, India. I''m interested in Regex','False','False',19);
INSERT INTO "user"(email,"name","password",join_date,reputation,bio,banned,expert,profile_photo) VALUES ('paulocarvalho6@youtu.be','paulinho33','$2b$12$tc8Rh0HYWWueqiHEreRAB.FRo1Y7HtGueNMGr7qLSS6E/ec3g.cgG','4/23/2020','1953','My name is Paulo Carvalho, I am 34 years old and I live in Funchal, Portugal. I''m interested in Data Science and PyCharm','False','True',20);



-- TAG TABLE
INSERT INTO tag("name","description",author_id) VALUES ('windows','Tag for questions related to the windows operating system','1');
INSERT INTO tag("name","description",author_id) VALUES ('linux','Tag for questions related to linux based distributions','1');
INSERT INTO tag("name","description",author_id) VALUES ('gnome','Tag for questions related to the GNOME desktop environment','1');
INSERT INTO tag("name","description",author_id) VALUES ('cmd','Tag for questions related to the Windows command line','1');
INSERT INTO tag("name","description",author_id) VALUES ('powershell','Tag for questions related to Powershell','1');
INSERT INTO tag("name","description",author_id) VALUES ('visual-studio-code','Tag for questions related to Microsoft''s Visual Studio Code (VSCode)','1');
INSERT INTO tag("name","description",author_id) VALUES ('git','Tag for questions related to Git, a version control system','1');
INSERT INTO tag("name","description",author_id) VALUES ('node.js','Tag for questions related to Node.js, a JavaScript runtime ','1');
INSERT INTO tag("name","description",author_id) VALUES ('markdown','Tag for questions related to Markdown, a lightweight markup language','1');
INSERT INTO tag("name","description",author_id) VALUES ('circle-ci','Tag for questions related to Circle CI, a tool for automating development process with continuous integration','1');
INSERT INTO tag("name","description",author_id) VALUES ('docker','Tag for questions related to Docker, a fast tool for containerizing applications','2');
INSERT INTO tag("name","description",author_id) VALUES ('intellij','Tag for questions related to IntelliJ, a very smart and clean Java IDE','2');
INSERT INTO tag("name","description",author_id) VALUES ('ide','Tag for questions related to Integrated Development Environment','2');
INSERT INTO tag("name","description",author_id) VALUES ('python','Tag for questions related to Python','2');
INSERT INTO tag("name","description",author_id) VALUES ('java','Tag for questions related to Java','2');
INSERT INTO tag("name","description",author_id) VALUES ('c/c++','Tag for questions related to C/C++','2');
INSERT INTO tag("name","description",author_id) VALUES ('php','Tag for questions related to PHP','2');
INSERT INTO tag("name","description",author_id) VALUES ('html/css','Tag for questions related to HTML and CSS','2');
INSERT INTO tag("name","description",author_id) VALUES ('javascript','Tag for questions related to JavaScript','2');
INSERT INTO tag("name","description",author_id) VALUES ('regex','Tag for questions related to Regex (Regular Expressions)','3');
INSERT INTO tag("name","description",author_id) VALUES ('xml','Tag for questions related to XML','3');
INSERT INTO tag("name","description",author_id) VALUES ('programming','Tag for questions related to general programming (or non listed on the tags) programming languages','3');
INSERT INTO tag("name","description",author_id) VALUES ('algorithms','Tag for questions related to algorithms','3');
INSERT INTO tag("name","description",author_id) VALUES ('api','Tag for questions related to APIs','3');
INSERT INTO tag("name","description",author_id) VALUES ('web-scraping','Tag for questions related to web scraping','3');
INSERT INTO tag("name","description",author_id) VALUES ('discord','Tag for questions related to Discord','3');
INSERT INTO tag("name","description",author_id) VALUES ('json','Tag for questions related to Java Script Object Notation','3');
INSERT INTO tag("name","description",author_id) VALUES ('pandas','Tag for questions related to Pandas Python library','3');
INSERT INTO tag("name","description",author_id) VALUES ('numpy','Tag for questions related to Numpy Python library','3');
INSERT INTO tag("name","description",author_id) VALUES ('sql','Tag for questions related to SQL','4');
INSERT INTO tag("name","description",author_id) VALUES ('database','Tag for questions related to database','4');
INSERT INTO tag("name","description",author_id) VALUES ('security','Tag for questions related to security','4');
INSERT INTO tag("name","description",author_id) VALUES ('machine-learning','Tag for questions related to machine learning','4');
INSERT INTO tag("name","description",author_id) VALUES ('ai','Tag for questions related to AI','4');
INSERT INTO tag("name","description",author_id) VALUES ('smartphones','Tag for questions related to Smartphones','4');
INSERT INTO tag("name","description",author_id) VALUES ('iphone','Tag for questions related to iPhone','4');
INSERT INTO tag("name","description",author_id) VALUES ('galaxy','Tag for questions related to Galaxy','4');
INSERT INTO tag("name","description",author_id) VALUES ('xiaomi','Tag for questions related to Xiaomi','4');
INSERT INTO tag("name","description",author_id) VALUES ('flutter','Tag for questions related to Flutter','4');
INSERT INTO tag("name","description",author_id) VALUES ('dom','Tag for questions related to DOM','4');
INSERT INTO tag("name","description",author_id) VALUES ('graphics-card','Tag for questions related to graphic cards','4');
INSERT INTO tag("name","description",author_id) VALUES ('videos','Tag for questions related to videos','8');
INSERT INTO tag("name","description",author_id) VALUES ('photos','Tag for questions related to photos','8');
INSERT INTO tag("name","description",author_id) VALUES ('android','Tag for questions related to android','8');
INSERT INTO tag("name","description",author_id) VALUES ('ios','Tag for questions related to iOS','8');
INSERT INTO tag("name","description",author_id) VALUES ('playstation','Tag for questions related to PlayStation','8');
INSERT INTO tag("name","description",author_id) VALUES ('xbox','Tag for questions related to XBOX','8');
INSERT INTO tag("name","description",author_id) VALUES ('mac','Tag for questions related to Mac','11');
INSERT INTO tag("name","description",author_id) VALUES ('data-science','Tag for questions related to Data Science','11');
INSERT INTO tag("name","description",author_id) VALUES ('web-applications','Tag for questions related to Web Applications','11');
INSERT INTO tag("name","description",author_id) VALUES ('cloud','Tag for questions related to Cloud','11');
INSERT INTO tag("name","description",author_id) VALUES ('google-drive','Tag for questions related to Google Drive','13');
INSERT INTO tag("name","description",author_id) VALUES ('social-media','Tag for questions related to Social Media','13');
INSERT INTO tag("name","description",author_id) VALUES ('testing','Tag for questions related to Software Testing','13');
INSERT INTO tag("name","description",author_id) VALUES ('tv','Tag for questions related to TVs','13');
INSERT INTO tag("name","description",author_id) VALUES ('sony','Tag for questions related to Sony','13');
INSERT INTO tag("name","description",author_id) VALUES ('samsung','Tag for questions related to Samsung','15');
INSERT INTO tag("name","description",author_id) VALUES ('keyboard','Tag for questions related to Keyboards','15');
INSERT INTO tag("name","description",author_id) VALUES ('mouse','Tag for questions related to Mouses','15');
INSERT INTO tag("name","description",author_id) VALUES ('monitor','Tag for questions related to Monitors','15');
INSERT INTO tag("name","description",author_id) VALUES ('memory-card','Tag for questions related to memory cards and disk storage','17');
INSERT INTO tag("name","description",author_id) VALUES ('computers','Tag for questions related to computers','17');
INSERT INTO tag("name","description",author_id) VALUES ('computer-vision','Tag for questions related to computer vision','17');
INSERT INTO tag("name","description",author_id) VALUES ('networks','Tag for questions related to computer networks ','20');
INSERT INTO tag("name","description",author_id) VALUES ('youtube','Tag for questions related to the YouTube platform','20');



-- CONTENT TABLE
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Are there any issues with using <code>async</code>/<code>await</code> in a <code>forEach</code> loop? I&#39;m trying to loop through an array of files and <code>await</code> on the contents of each file.
<pre><code class="lang-js"><span class="hljs-keyword">import</span> fs <span class="hljs-keyword">from</span> <span class="hljs-string">"fs-promise"</span>;

<span class="hljs-keyword">async</span> <span class="hljs-function"><span class="hljs-keyword">function</span> <span class="hljs-title">printFiles</span>(<span class="hljs-params"></span>) </span>{
  <span class="hljs-keyword">const</span> files = <span class="hljs-keyword">await</span> getFilePaths(); <span class="hljs-comment">// Assume this works fine</span>

  files.forEach(<span class="hljs-keyword">async</span> (file) =&gt; {
    <span class="hljs-keyword">const</span> contents = <span class="hljs-keyword">await</span> fs.readFile(file, <span class="hljs-string">"utf8"</span>);
    <span class="hljs-built_in">console</span>.log(contents);
  });
}

printFiles();
</code></pre>
This code does work, but could something go wrong with this? I had someone tell me that you&#39;re not supposed to use <code>async</code>/<code>await</code> in a higher-order function like this, so I just wanted to ask if there was any issue with this.','2021-02-19 10:16:07','2021-02-19 23:28:07',5);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('How can you find out which process is listening on a TCP or UDP port on Windows?','2021-02-19 10:44:55',NULL,6);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('If I go to settings -&gt; iCloud -&gt; manage storage There are 2 categories:
<ol>
<li>Backups (118 GB)</li>
<li>Photos (18.3 GB)</li>
</ol>
In my backups, there&#39;s photo library (109.25 GB)
I&#39;m confused, what&#39;s the difference between storing my photos in iCloud and backing them up then storing them in iCloud.
What happens if I click on &quot; turn off and delete&quot; the backup for my photo library?','2021-02-19 11:13:43',NULL,7);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('<code>pip</code> is a replacement for <code>easy_install</code>. But should I install <code>pip</code> using <code>easy_install</code> on Windows? Is there a better way?','2021-02-19 11:42:31',NULL,8);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('How to remove the debug banner in flutter?
I am using flutter screenshot and I would like the screenshot not to have banner. Now it does have.
Note that I get not supported for emulator message for profile and release mode.
','2021-02-19 12:11:19',NULL,9);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('I have a question. I am subscribed to PS+, and I started downloading a free game in June. Can I download it anytime I want or is there a deadline before they charge me for it?','2021-02-19 12:40:07',NULL,10);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('I am really interested in AI and want to start programming in this field. What are the various areas within AI? e.g. Neural Networks etc.
What book can be recommended for a beginner in AI and are there any preferred languages used in the field of AI?
What book can be recommended for a beginner in AI and are there any preferred languages used in the field of AI?','2021-02-19 13:08:55',NULL,11);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('I am an amateur game developer, and I&#39;ve been reading a lot on how computer graphics rendering works lately. I was reading on the Z-buffer recently and I can&#39;t quite seem to be able to wrap my head around what exactly the Z-buffer looks like in terms of memory. It&#39;s described to contain depth information for each fragment that will be drawn on-screen, and that modern Z-buffers have 32-bits, so would that mean that on a, let&#39;s say, 1920x1080 screen it&#39;d be just above 8MB (1920 <em> 1080 </em> 32) per frame?
I still don&#39;t quite understand what this value would be to a GPU (maybe it can crunch it easily), or if this value is even correct. Most demostrative implementations I found implement the Z-buffer as a simple array with size (height * width), so I&#39;m basing myself off of that.','2021-02-19 13:37:43',NULL,12);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('When viewing videos with the YouTube app, constant info message popups during playback shows. Videos that show those info popups show a white circle with the letter &#39;i&#39;.
Is there a way to disable that?','2021-02-19 14:06:31',NULL,13);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Can I use comments inside a JSON file? If so, how?','2021-02-19 14:35:19','2021-02-19 15:47:19',14);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Sure the code does work, but I&#39;m pretty sure it doesn&#39;t do what you expect it to do. It just fires off multiple asynchronous calls, but the <code>printFiles</code> function does immediately return after that.
<h3 id="reading-in-sequence">Reading in sequence</h3>
If you want to read the files in sequence, you cannot use forEach indeed. Just use a modern for … of loop instead, in which await will work as expected:
<pre><code class="lang-js"><span class="hljs-keyword">async</span> <span class="hljs-function"><span class="hljs-keyword">function</span> <span class="hljs-title">printFiles</span>(<span class="hljs-params"></span>) </span>{
  <span class="hljs-keyword">const</span> files = <span class="hljs-keyword">await</span> getFilePaths();

  <span class="hljs-keyword">for</span> (<span class="hljs-keyword">const</span> file <span class="hljs-keyword">of</span> files) {
- <span class="hljs-keyword">const</span> contents = <span class="hljs-keyword">await</span> fs.readFile(file, <span class="hljs-string">"utf8"</span>);
- <span class="hljs-built_in">console</span>.log(contents);
  }
}
</code></pre>
<h3 id="reading-in-parallel">Reading in parallel</h3>
If you want to read the files in parallel, you cannot use <code>forEach</code> indeed. Each of the <code>async</code> callback function calls does return a promise, but you&#39;re throwing them away instead of awaiting them. Just use map instead, and you can await the array of promises that you&#39;ll get with <code>Promise.all</code>:
```js
async function printFiles() {
  const files = await getFilePaths();
  await Promise.all(
<ul>
<li>files.map(async (file) =&gt; {</li>
<li>const contents = await fs.readFile(file, &quot;utf8&quot;);</li>
<li>console.log(contents);</li>
<li>})
);
}</li>
</ul>
','2021-02-19 15:04:07',NULL,20);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('<pre><code class="lang-js"><span class="hljs-keyword">const</span> { forEach } = <span class="hljs-built_in">require</span>(<span class="hljs-string">"p-iteration"</span>);
<span class="hljs-keyword">const</span> fs = <span class="hljs-built_in">require</span>(<span class="hljs-string">"fs-promise"</span>);

<span class="hljs-keyword">async</span> <span class="hljs-function"><span class="hljs-keyword">function</span> <span class="hljs-title">printFiles</span>(<span class="hljs-params"></span>) </span>{
  <span class="hljs-keyword">const</span> files = <span class="hljs-keyword">await</span> getFilePaths();

  <span class="hljs-keyword">await</span> forEach(files, <span class="hljs-keyword">async</span> (file) =&gt; {
    <span class="hljs-keyword">const</span> contents = <span class="hljs-keyword">await</span> fs.readFile(file, <span class="hljs-string">"utf8"</span>);
    <span class="hljs-built_in">console</span>.log(contents);
  });
}
</code></pre>','2021-02-19 15:32:55',NULL,19);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('<h3 id="on-powershell">On Powershell</h3>
TCP
<pre><code><span class="hljs-keyword">Get</span>-Process -Id (<span class="hljs-keyword">Get</span>-NetTCPConnection -LocalPort YourPortNumberHere).OwningProcess
</code></pre>UDP
<pre><code><span class="hljs-keyword">Get</span>-Process -Id (<span class="hljs-keyword">Get</span>-NetUDPEndpoint -LocalPort YourPortNumberHere).OwningProcess
</code></pre><h3 id="on-cmd">On CMD</h3>
<pre><code>C:\&gt; netstat -<span class="hljs-selector-tag">a</span> -b
</code></pre>','2021-02-19 16:01:43',NULL,18);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Cloud Photos is for syncing between your devices and replacing your photos if you lose your device. It retains a full copy of your photos in iCloud (accessible on iCloud.com as well).

iCloud Backup is a one-way backup of your current photo library on your device. It can only be restored by restoring your entire iCloud Backup.

You can safely turn off Photos from your backup - unless you prefer to manually manage your photos, I''d recommend doing this instead of turning off iCloud Photos.

If iCloud Photo Library is on, there will be a note saying "Photo Library is backed up separately as part of iCloud Photos" - if you don''t see this message, double check that your device is actually syncing with iCloud Photos. You can do this in the Photos Setting or in iCloud Settings > Photos.','2021-02-19 16:30:31',NULL,17);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('<pre><code><span class="hljs-keyword">python</span> <span class="hljs-built_in">get</span>-pip.<span class="hljs-keyword">py</span>
</code></pre>','2021-02-19 16:59:19',NULL,16);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('On your <code>MaterialApp</code> set <code>debugShowCheckedModeBanner</code> to <code>false</code>.
<pre><code class="lang-dart"><span class="hljs-selector-tag">MaterialApp</span>(
  <span class="hljs-attribute">debugShowCheckedModeBanner</span>: false,
)
</code></pre>
The debug banner will also automatically be removed on release build.
','2021-02-19 17:28:07',NULL,15);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Once you added the PS+ game to your library, you can download and play it any time, as long as you still have PS+. If you stop having PS+, you can no longer play/download the game AFAIK.','2021-02-19 17:56:55',NULL,14);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Classical application areas of AI:
<ul>
<li>Robotics</li>
<li>Search</li>
<li>Natural Language Processing</li>
<li>Knowledge Representation / Expert Systems</li>
<li>Planning / Scheduling</li>
</ul>
Various algorithmic approaches:
<ul>
<li>Neural Networks</li>
<li>Evolutionary / Genetic Algorithms</li>
<li>Automatic Reasoning</li>
<li>Logic Programming</li>
<li>Probablilistic Approaches</li>
</ul>
Recommendable books:
<ul>
<li>Norvig, Russel: Artificial Intelligence - A Modern Approach</li>
<li>Norvig: Paradigms of Artificial Intelligence Programming (uses Lisp)</li>
<li>Bratko: Prolog Programming for Artificial Intelligence</li>
</ul>
Recommendable programming languages:
<ul>
<li>Prolog</li>
<li>Lisp</li>
<li>Java (many algorithms are discussed in Java nowadays)</li>
</ul>
There are also a number of interesting answers to this question (which sort of covers the same ground).','2021-02-19 18:25:43',NULL,13);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('The Z buffer used to be specialized memory set aside for a single purpose, some web sites still explain it like that, but no longer.
Now the Z buffer is just a chunk of memory you allocate, or an API like OpenGL allocates on your behalf.
The size of that chunk of memory will depend on the type of the Z buffer values, in the example you gave it is a 32bit [floating point] values, but 24 bits is also very common. Always choose the smallest size the program needs as it can have a large effect on the performance of the application. It is indeed multiplied by the size of framebuffer so 8mb is correct for the example you gave.
The values that get stored in it are the depth values for any geometry drawn to the associated framebuffer. It is important to realize that these values are NOT the linear values of the MVP matrix computed in the vertex shader so the Z buffer can not be used for things like shadow maps.
Fragments resulting from each draw call have their depth values tested against existing values in the Z buffer and if the test passes the GPU will write that fragment and update the Z buffer with the new value, if not the fragment gets discarded and the Z buffer is left untouched.
A few other little details:
The Z buffer is generally cleared at the beginning of each frame with a clear value that can be set (or must be set) via the API. This becomes the default value that writes to the Z buffer are tested against.
GPU&#39;s have specialized hardware for writing the Z buffer, this hardware can speed up writing to memory by a factor of 2 or more and can be leveraged when creating things like shadow maps, so it is not limited for use with just the Z buffer.
Depth testing can be turned off/on for each draw call, which can be useful.
','2021-02-19 18:54:31',NULL,12);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('No.
The JSON is data only, and if you include a comment, then it will be data too.
You could have a designated data element called &quot;_comment&quot; (or something) that should be ignored by apps that use the JSON data.
You would probably be better having the comment in the processes that generates/receives the JSON, as they are supposed to know what the JSON data will be in advance, or at least the structure of it.','2021-02-19 19:23:19',NULL,11);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Could you please explain why does <code>for ... of ...</code> work?','2021-02-19 19:52:07',NULL,18);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Ok i know why... Using <strong>Babel</strong> will transform <code>async</code>/<code>await</code> to generator function and using <code>forEach</code> means that each iteration has an individual generator function, which has nothing to do with the others. so they will be executed independently and has no context of <code>next()</code> with others. Actually, a simple <code>for()</code> loop also works because the iterations are also in one single generator function.
','2021-02-19 20:20:55',NULL,19);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('In short, because it was designed to work :-) await suspends the current function evaluation, including all control structures. Yes, it is quite similar to generators in that regard (which is why they are used to polyfill async/await).','2021-02-19 20:49:43',NULL,20);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('So <code>files.map(async (file) =&gt; ...</code> is equivalent to <code>files.map((file) =&gt; new Promise((rej, res) =&gt; { ...</code>?','2021-02-19 21:18:31',NULL,18);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Not really, an async function is quite different from a Promise executor callback, but yes the map callback returns a promise in both cases.
','2021-02-19 21:47:19',NULL,20);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('When you come to learn about JS promises, but instead use half an hour translating latin ;)
','2021-02-19 22:16:07',NULL,20);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Worked for me! Thanks a lot!','2021-02-19 22:44:55',NULL,7);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('You''re welcome :D','2021-02-19 23:13:43',NULL,6);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Worked for me on Powershell. Thanks!','2021-02-19 23:42:31',NULL,9);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('You''re welcome :)','2021-02-20 00:11:19',NULL,8);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('Great question. Should be bountied!','2021-02-20 00:40:07',NULL,10);
INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ('I really need an answer for this!','2021-02-20 01:08:55',NULL,11);



-- QUESTION TABLE
INSERT INTO question(content_id,title,votes_difference) VALUES (1,'Using async/await with a forEach loop',2);
INSERT INTO question(content_id,title,votes_difference) VALUES (2,'How can you find out which process is listening on a TCP or UDP port on Windows?',0);
INSERT INTO question(content_id,title,votes_difference) VALUES (3,'Whats the difference between backing up my photos on iPhone and storing them in iCloud?',4);
INSERT INTO question(content_id,title,votes_difference) VALUES (4,'How can I install pip on Windows?',-3);
INSERT INTO question(content_id,title,votes_difference) VALUES (5,'How to remove the Flutter debug banner?',1);
INSERT INTO question(content_id,title,votes_difference) VALUES (6,'PS4 free monthly games',5);
INSERT INTO question(content_id,title,votes_difference) VALUES (7,'Beginning AI programming',-2);
INSERT INTO question(content_id,title,votes_difference) VALUES (8,'What does the z-buffer look like in memory?',-1);
INSERT INTO question(content_id,title,votes_difference) VALUES (9,'Disable info popups on YouTube app',1);
INSERT INTO question(content_id,title,votes_difference) VALUES (10,'Can comments be used in JSON?',1);



-- ANSWER TABLE
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (11,-1,1);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (12,4,1);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (13,2,2);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (14,4,3);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (15,-1,4);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (16,2,5);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (17,-1,6);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (18,4,7);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (19,3,8);
INSERT INTO answer(content_id,votes_difference,question_id) VALUES (20,0,10);



-- ANSWER COMMENT TABLE
INSERT INTO answer_comment(content_id,answer_id) VALUES (21,11);
INSERT INTO answer_comment(content_id,answer_id) VALUES (22,11);
INSERT INTO answer_comment(content_id,answer_id) VALUES (23,11);
INSERT INTO answer_comment(content_id,answer_id) VALUES (24,11);
INSERT INTO answer_comment(content_id,answer_id) VALUES (25,11);
INSERT INTO answer_comment(content_id,answer_id) VALUES (26,11);
INSERT INTO answer_comment(content_id,answer_id) VALUES (27,12);
INSERT INTO answer_comment(content_id,answer_id) VALUES (28,12);
INSERT INTO answer_comment(content_id,answer_id) VALUES (29,14);
INSERT INTO answer_comment(content_id,answer_id) VALUES (30,14);



-- QUESTION COMMENT TABLE
INSERT INTO question_comment(content_id,question_id) VALUES (31,1);
INSERT INTO question_comment(content_id,question_id) VALUES (32,1);



-- MODERATOR TABLE
INSERT INTO moderator("user_id",questions_deleted,answers_deleted,comments_deleted,banned_users,solved_reports) VALUES (1,0,2,0,1,1);
INSERT INTO moderator("user_id",questions_deleted,answers_deleted,comments_deleted,banned_users,solved_reports) VALUES (2,2,1,0,2,0);
INSERT INTO moderator("user_id",questions_deleted,answers_deleted,comments_deleted,banned_users,solved_reports) VALUES (3,0,0,0,0,0);
INSERT INTO moderator("user_id",questions_deleted,answers_deleted,comments_deleted,banned_users,solved_reports) VALUES (4,1,0,0,3,2);



-- NOTIFICATION TABLE
INSERT INTO notification(type,content,icon,"date","user_id") VALUES ('answered','ola','icon1','2021-03-11 10:16:07',5);
INSERT INTO notification(type,content,icon,"date","user_id") VALUES ('answered_saved','ola','icon2','2021-03-13 10:16:07',6);
INSERT INTO notification(type,content,icon,"date","user_id") VALUES ('upvote_question','ola','icon3','2021-03-15 10:16:07',7);
INSERT INTO notification(type,content,icon,"date","user_id") VALUES ('upvote_answer','ola','icon4','2021-03-17 10:16:07',8);
INSERT INTO notification(type,content,icon,"date","user_id") VALUES ('best_answer','ola','icon5','2021-03-19 10:16:07',9);



-- BAN TABLE
INSERT INTO ban("start","end",reason,"user_id", moderator_id) VALUES ('2020-10-23 12:12:45','2020-10-30 04:41:42','User showed a growing pattern of rudeness',17,1);
INSERT INTO ban("start","end",reason,"user_id", moderator_id) VALUES ('2020-03-21 21:33:43','2020-04-21 09:00:08','User gravely disrespected terms of service in post',10,2);
INSERT INTO ban("start","end",reason,"user_id", moderator_id) VALUES ('2020-02-22 19:18:15','2021-02-22 13:47:58','User harrassed multiple users with comments',8,2);



-- REPORT TABLE
INSERT INTO report("description",solved,reporter_id,solver_id) VALUES ('This user is continuously being rude and insulting','True',7,3);
INSERT INTO report("description",solved,reporter_id,solver_id) VALUES ('I am pretty confident this user gravely disrespected terms of service and community guidelines with this post','True',9,2);
INSERT INTO report("description",solved,reporter_id,solver_id) VALUES ('I don''t think this content should be up','False',11,1);
INSERT INTO report("description",solved,reporter_id,solver_id) VALUES ('This user is harrassing a group of people with their comments','True',5,3);
INSERT INTO report("description",solved,reporter_id,solver_id) VALUES ('I think this content violates community guidelines','False',6,3);



-- USER REPORT TABLE
INSERT INTO user_report(report_id,"user_id") VALUES (1,17);
INSERT INTO user_report(report_id,"user_id") VALUES (2,10);
INSERT INTO user_report(report_id,"user_id") VALUES (4,8);



-- CONTENT REPORT TABLE
INSERT INTO content_report(report_id,content_id) VALUES (3,4);
INSERT INTO content_report(report_id,content_id) VALUES (5,9);



-- FOLLOW TAG TABLE
INSERT INTO follow_tag(tag_id,"user_id") VALUES (9,1);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (43,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (23,11);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (3,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (4,11);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (8,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (24,11);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (38,17);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (16,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (14,12);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (40,12);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (14,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (10,5);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (6,8);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (10,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (19,15);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (46,13);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (47,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (34,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (47,11);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (33,17);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (42,5);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (46,11);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (3,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (23,7);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (29,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (35,18);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (10,7);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (23,14);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (10,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (15,8);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (37,18);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (9,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (19,8);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (23,17);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (39,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (48,18);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (10,12);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (13,17);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (43,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (7,17);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (41,18);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (25,11);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (27,14);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (7,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (11,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (2,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (20,14);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (21,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (12,10);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (6,15);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (24,8);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (20,5);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (12,11);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (44,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (40,15);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (47,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (34,15);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (46,12);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (50,5);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (10,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (8,10);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (26,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (17,7);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (34,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (31,13);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (4,17);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (30,14);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (28,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (34,20);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (15,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (36,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (12,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (11,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (9,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (47,8);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (21,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (50,12);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (33,4);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (7,20);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (36,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (11,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (31,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (5,7);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (44,6);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (41,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (27,9);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (30,18);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (18,15);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (16,10);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (19,13);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (21,18);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (15,5);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (33,19);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (32,20);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (47,16);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (25,5);
INSERT INTO follow_tag(tag_id,"user_id") VALUES (34,11);



-- USER VOTE QUESTION TABLE
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (1,3,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (12,5,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (19,2,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (11,3,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (10,3,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (5,7,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (19,9,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (20,10,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (15,4,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (6,6,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (14,4,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (19,4,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (16,8,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (15,7,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (11,8,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (12,7,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (16,5,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (7,6,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (16,10,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (17,3,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (15,5,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (12,1,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (11,2,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (9,9,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (5,9,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (18,7,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (10,7,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (13,1,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (10,8,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (5,2,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (13,3,-1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (5,8,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (8,7,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (17,7,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (18,6,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (18,10,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (10,10,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (16,1,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (8,4,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (13,9,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (20,9,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (14,9,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (6,8,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (11,4,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (16,9,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (16,7,1);
INSERT INTO user_vote_question("user_id",question_id,vote) VALUES (8,2,1);



-- USER VOTE ANSWER TABLE
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (1,11,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (2,12,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (3,13,-1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (4,14,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (5,15,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (6,16,-1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (7,17,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (8,18,-1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (9,19,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (10,20,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (11,11,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (12,12,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (13,13,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (14,14,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (15,15,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (16,16,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (17,17,1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (18,18,-1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (19,19,-1);
INSERT INTO user_vote_answer("user_id",answer_id,vote) VALUES (20,12,1);



-- SAVED QUESTION TABLE
INSERT INTO saved_question("user_id",question_id) VALUES (15,2);
INSERT INTO saved_question("user_id",question_id) VALUES (16,8);
INSERT INTO saved_question("user_id",question_id) VALUES (4,3);
INSERT INTO saved_question("user_id",question_id) VALUES (7,1);
INSERT INTO saved_question("user_id",question_id) VALUES (11,6);
INSERT INTO saved_question("user_id",question_id) VALUES (12,6);
INSERT INTO saved_question("user_id",question_id) VALUES (18,10);
INSERT INTO saved_question("user_id",question_id) VALUES (13,7);
INSERT INTO saved_question("user_id",question_id) VALUES (14,5);
INSERT INTO saved_question("user_id",question_id) VALUES (9,1);
INSERT INTO saved_question("user_id",question_id) VALUES (11,4);
INSERT INTO saved_question("user_id",question_id) VALUES (11,2);
INSERT INTO saved_question("user_id",question_id) VALUES (16,5);
INSERT INTO saved_question("user_id",question_id) VALUES (14,7);
INSERT INTO saved_question("user_id",question_id) VALUES (14,8);
INSERT INTO saved_question("user_id",question_id) VALUES (7,8);
INSERT INTO saved_question("user_id",question_id) VALUES (14,9);
INSERT INTO saved_question("user_id",question_id) VALUES (20,10);
INSERT INTO saved_question("user_id",question_id) VALUES (10,8);
INSERT INTO saved_question("user_id",question_id) VALUES (6,1);



-- QUESTION TAG TABLE
INSERT INTO question_tag(question_id,tag_id) VALUES (1,19);
INSERT INTO question_tag(question_id,tag_id) VALUES (1,8);
INSERT INTO question_tag(question_id,tag_id) VALUES (2,1);
INSERT INTO question_tag(question_id,tag_id) VALUES (2,64);
INSERT INTO question_tag(question_id,tag_id) VALUES (3,36);
INSERT INTO question_tag(question_id,tag_id) VALUES (3,51);
INSERT INTO question_tag(question_id,tag_id) VALUES (3,43);
INSERT INTO question_tag(question_id,tag_id) VALUES (4,14);
INSERT INTO question_tag(question_id,tag_id) VALUES (4,1);
INSERT INTO question_tag(question_id,tag_id) VALUES (5,39);
INSERT INTO question_tag(question_id,tag_id) VALUES (5,35);
INSERT INTO question_tag(question_id,tag_id) VALUES (6,46);
INSERT INTO question_tag(question_id,tag_id) VALUES (7,34);
INSERT INTO question_tag(question_id,tag_id) VALUES (7,33);
INSERT INTO question_tag(question_id,tag_id) VALUES (8,41);
INSERT INTO question_tag(question_id,tag_id) VALUES (8,62);
INSERT INTO question_tag(question_id,tag_id) VALUES (9,65);
INSERT INTO question_tag(question_id,tag_id) VALUES (9,35);
INSERT INTO question_tag(question_id,tag_id) VALUES (10,27);
