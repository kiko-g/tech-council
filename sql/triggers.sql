--------------------------------------
-- UPDATE USER AND QUESTION REPUTATION 
--------------------------------------

-- on insert to question table
DROP FUNCTION IF EXISTS insert_reputation_question CASCADE;
CREATE FUNCTION insert_reputation_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.upvote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id") VALUES ('upvote_question', 'upvote question notify',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.question_id)));
    END IF;

    -- update user reputation
    UPDATE "user" SET reputation = reputation + NEW.upvote
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
    UPDATE question SET votes_difference = votes_difference + NEW.upvote
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
    IF NEW.upvote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id") VALUES ('upvote_question', 'upvote question notify',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.answer_id)));
    END IF;

    -- update user reputation
    UPDATE "user" SET reputation = reputation + NEW.upvote
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
    UPDATE answer SET votes_difference = votes_difference + NEW.upvote
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
DECLARE rep integer := NEW.upvote - OLD.upvote;
BEGIN
    IF NEW.upvote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id") VALUES ('upvote_question', 'upvote question notify',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.question_id)));
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
DECLARE rep integer := NEW.upvote - OLD.upvote;
BEGIN
    IF NEW.upvote = 1 THEN
        INSERT INTO "notification" ("type", content, "user_id") VALUES ('upvote_question', 'upvote question notify',
            (SELECT author_id FROM content 
                WHERE (content.id = NEW.answer_id)));
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
    UPDATE "user" SET reputation = reputation - OLD.upvote
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
    UPDATE question SET votes_difference = votes_difference - OLD.upvote
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
    UPDATE "user" SET reputation = reputation - OLD.upvote
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
    UPDATE answer SET votes_difference = votes_difference - OLD.upvote
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
        SELECT * FROM report WHERE id = NEW.report_id AND reporter_id = NEW."user_id"
    ) THEN
        DELETE FROM report WHERE id = NEW.report_id;
        RETURN NULL;
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
        DELETE FROM report WHERE id = NEW.report_id;
        RETURN NULL;
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

--------------------------------------
-- NOTIFICATION RELATED TRIGGERS 
--------------------------------------

-- answer to user question
DROP FUNCTION IF EXISTS notify_answer CASCADE;
CREATE FUNCTION notify_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO "notification" ("type", content, "user_id") VALUES ('answered', 'new answer notify',
        (SELECT author_id FROM content 
            WHERE (content.id = NEW.question_id)));

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
    INSERT INTO "notification" ("type", content, "user_id") 
        SELECT 'answered_saved', 'answered saved', "user_id" 
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
