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
