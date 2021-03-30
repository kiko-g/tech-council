---------------------------
-- UPDATE USER (AND QUESTION?) REPUTATION 
---------------------------

-- on insert to question table

DROP TRIGGER IF EXISTS insert_reputation_question ON user_vote_question;
DROP FUNCTION IF EXISTS insert_reputation_question CASCADE;

CREATE FUNCTION insert_reputation_question() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.upvote <> 0 THEN
        UPDATE "user" SET reputation = reputation + NEW.upvote
        WHERE "user".id = (SELECT author_id FROM content 
                            WHERE (content.id = NEW.question_id)); 
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER insert_reputation_question
    AFTER INSERT ON user_vote_question
    FOR EACH ROW
    EXECUTE PROCEDURE insert_reputation_question();

-- on insert to answer table

DROP TRIGGER IF EXISTS insert_reputation_answer ON user_vote_answer;
DROP FUNCTION IF EXISTS insert_reputation_answer CASCADE;

CREATE FUNCTION insert_reputation_answer() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.upvote <> 0 THEN
        UPDATE "user" SET reputation = reputation + NEW.upvote
        WHERE "user".id = (SELECT author_id FROM content 
                            WHERE (content.id = NEW.answer_id)); 
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER insert_reputation_answer
    AFTER INSERT ON user_vote_answer
    FOR EACH ROW
    EXECUTE PROCEDURE insert_reputation_answer();

-- on update to question table

DROP TRIGGER IF EXISTS update_reputation_question ON user_vote_question;
DROP FUNCTION IF EXISTS update_reputation_question CASCADE;

CREATE FUNCTION update_reputation_question() RETURNS TRIGGER AS
$BODY$
DECLARE rep integer := NEW.upvote - OLD.upvote;
BEGIN
    IF rep <> 0 THEN
        UPDATE "user" SET reputation = reputation + rep
        WHERE "user".id = (SELECT author_id FROM content 
                            WHERE (content.id = NEW.question_id)); 
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_reputation_question
    AFTER UPDATE ON user_vote_question
    FOR EACH ROW
    EXECUTE PROCEDURE update_reputation_question();

-- on update to answer table

DROP TRIGGER IF EXISTS update_reputation_answer ON user_vote_answer;
DROP FUNCTION IF EXISTS update_reputation_answer CASCADE;

CREATE FUNCTION update_reputation_answer() RETURNS TRIGGER AS
$BODY$
DECLARE rep integer := NEW.upvote - OLD.upvote;
BEGIN
    IF rep <> 0 THEN
        UPDATE "user" SET reputation = reputation + rep
        WHERE "user".id = (SELECT author_id FROM content 
                            WHERE (content.id = NEW.answer_id)); 
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_reputation_answer
    AFTER UPDATE ON user_vote_answer
    FOR EACH ROW
    EXECUTE PROCEDURE update_reputation_answer();

-- on delete from question table

-- on delete from answer table

---------------------------
-- Set user default photo
---------------------------