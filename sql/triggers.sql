---------------------------
-- UPDATE USER AND QUESTION REPUTATION 
---------------------------

-- on insert to question table
DROP FUNCTION IF EXISTS insert_reputation_question CASCADE;
CREATE FUNCTION insert_reputation_question() RETURNS TRIGGER AS
$BODY$
BEGIN
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
