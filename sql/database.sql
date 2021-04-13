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
-- Create tables
------------------

--- TODO: add creator
CREATE TABLE tag (
    id SERIAL PRIMARY KEY,
    "name" TEXT UNIQUE NOT NULL,
    "description" TEXT NOT NULL
);

CREATE TABLE photo (
    id SERIAL PRIMARY KEY,
    path TEXT NOT NULL UNIQUE -- path might be a keyword
);

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    email TEXT NOT NULL UNIQUE,
    "name" TEXT NOT NULL,
    "password" TEXT NOT NULL,
    join_date DATE NOT NULL DEFAULT NOW(),
    reputation INTEGER NOT NULL DEFAULT 0,
    bio TEXT,
    expert BOOLEAN NOT NULL DEFAULT FALSE,
    banned BOOLEAN NOT NULL DEFAULT FALSE,
    profile_photo INTEGER REFERENCES photo(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE content (
    id SERIAL PRIMARY KEY,
    main TEXT NOT NULL,
    creation_date DATE NOT NULL DEFAULT NOW(),
    modification_date DATE DEFAULT NULL,
    author_id INTEGER REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE SET NULL,
    edited BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT mod_after_cre CHECK(modification_date > creation_date)
);

CREATE TABLE question (
    content_id INTEGER PRIMARY KEY REFERENCES content(id) ON UPDATE CASCADE ON DELETE CASCADE,
    title TEXT NOT NULL,
    votes_difference INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE answer (
    content_id INTEGER PRIMARY KEY REFERENCES content(id) ON UPDATE CASCADE ON DELETE CASCADE,
    votes_difference INTEGER NOT NULL DEFAULT 0,
    question_id INTEGER REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE
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
    type TEXT NOT NULL, -- TODO: define types
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
    tag_id INTEGER NOT NULL REFERENCES tag(id) ON UPDATE CASCADE ON DELETE CASCADE,
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (tag_id, "user_id")
);

CREATE TABLE user_vote_question (
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    question_id INTEGER NOT NULL REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    vote BOOLEAN NOT NULL,
    PRIMARY KEY ("user_id", question_id)
);

CREATE TABLE user_vote_answer (
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    answer_id INTEGER NOT NULL REFERENCES answer(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    vote BOOLEAN NOT NULL,
    PRIMARY KEY ("user_id", answer_id)
);

CREATE TABLE saved_question (
    "user_id" INTEGER NOT NULL REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE,
    question_id INTEGER NOT NULL REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY ("user_id", question_id)
);

CREATE TABLE question_tag (
    question_id INTEGER NOT NULL REFERENCES question(content_id) ON UPDATE CASCADE ON DELETE CASCADE,
    tag_id INTEGER NOT NULL REFERENCES tag(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (question_id, tag_id)
);