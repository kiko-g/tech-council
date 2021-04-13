/*  SELECT01 
 *  Selecting a question's content from its ID
 *  Thousands per day
 */
SELECT title, votes_difference, main, creationDate, edited, author_id
FROM content INNER JOIN question on id = content_id
WHERE id = $question_id

/* SELECT02
 * Select a question's tags
 * Thousands per day
 */
SELECT id, name
FROM tag INNER JOIN question_tag on id = tag_id
WHERE tag.question_id = $question_id
 
/* SELECT03
 * Select a question's comments
 * Thousands per day
 */
 SELECT cm.content_id, c.main, c.edited, c.creation_date
 FROM content c INNER JOIN question_comment qc ON c.id = qc.content_id
 WHERE qc.question_id = $question_id

/* SELECT04
 * Select a question's answers
 * Thousands per day
 */
 SELECT a.content_id, c.main, a.votes_difference, a.creation_date, c.edited, c.author_id
 FROM content c INNER JOIN answer a ON c.id = a.content_id
 WHERE a.question_id = $question_id

 /* SELECT05
 * Select an answer's comments
 * Thousands per day
 */
 SELECT cm.content_id, c.main, c.edited, c.creation_date
 FROM content c INNER JOIN answer_comment ac ON c.id = ac.content_id
 WHERE ac.answer_id = $answer_id

/* SELECT06
 * Retrieve information about a question's author
 * Thousands per day
 */
SELECT name, reputation, bio, expert, banned
FROM "user" u
WHERE u.id = $author_id;

/* SELECT07
 * User profile information
 * Hundreds per day
 */
SELECT "name", email, bio, join_date, expert, banned, path as photo_path
FROM "user" u INNER JOIN photo p ON u.photo_id = p.id
WHERE u.id = $user_id;

/* SELECT08
 * Questions with a user's followed tags
 * Hundreds per day
 */
SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited
FROM content c INNER JOIN question q ON c.id = q.content_id
WHERE EXISTS (
    SELECT t.name
    FROM tag t INNER JOIN follow_tag ft ON t.id = ft.tag_id INNER JOIN question_tag qt on t.id = qt.tag_id
    WHERE ft.user_id = $user_id AND qt.question_id = q.id
)
ORDER BY c.creation_date DESC;

/* SELECT09
 * User's saved questions
 * Hundreds per day
 */
SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited
FROM saved_question sq INNER JOIN question q ON sq.question_id = q.id  INNER JOIN content c ON c.id = q.content_id
WHERE sq."user_id" = $user_id;

/* SELECT10
 * Questions made by a User
 * Hundreds per day
 */
SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited
FROM content c INNER JOIN question q ON c.id = q.content_id
WHERE c.author_id = $user_id;

/* SELECT11
 * Answers made by a User
 * Hundreds per day
 */
SELECT q.title, a.votes_difference, c.main, c.creation_date, c.edited
FROM content c INNER JOIN answer a ON c.id = a.contend_id INNER JOIN question q ON a.question_id = q.content_id
WHERE c.author_id = $user_id;

/* SELECT12
 * Pending notifications for a User
 * Thousands per day
 */
SELECT n.type, n.content, n.icon, n."date"
FROM "notification" n
WHERE n.user_id = $user_id;

/* SELECT13
 * Get tags followed by a User
 * Thousands per day
 */
SELECT t.name
FROM tag t inner join follow_tag ft on t.id = ft.tag_id
WHERE ft.user_id = $user_id;
 
/* SELECT14
 * Select trending questions from the last week (for example)
 * Thousands per day
 */
SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited, c.author_id
FROM content c INNER JOIN question q ON c.id = q.content_id
WHERE date_part('day', now()- c.creation_date) < 7
ORDER BY q.votes_difference DESC
LIMIT $max_num_questions;

/* SELECT15
 * Select latest questions
 * Thousands per day
 */
SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited, c.author_id
FROM content c INNER JOIN question q ON c.id = q.content_id
ORDER BY q.creation_date DESC
LIMIT $max_num_questions;

/* SELECT16
 * Select questions with a given tag
 * Thousands per day
 */
SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited, c.author_id
FROM content c INNER JOIN question q ON c.id = q.content_id
WHERE EXISTS (
    SELECT t.name
    FROM tag t INNER JOIN question_tag qt on t.id = qt.tag_id
    WHERE qt.question_id = q.id
)
ORDER BY c.creation_date DESC;

/* SELECT17
 * Search results for questions
 * Thousands per day
 */
SELECT q.title, q.votes_difference, c.main, c.creation_date, c.edited, u.name, rank
FROM content c inner join question q on c.id = q.content_id inner join "user" u on c.author_id = u.id,
ts_rank_cd(setweight(to_tsvector('simple', q.title), 'A') || ' ' || setweight(to_tsvector('simple', c.main), 'B') || ' ' || setweight(to_tsvector('simple', u.name), 'D'), plainto_tsquery('simple', $search_str)) as rank
WHERE rank > 0
ORDER BY rank DESC;

/* SELECT18
 * Search results for tags
 * Thousands per day
 */
SELECT t.name, t.description, rank
FROM tag t,
ts_rank_cd(to_tsvector(t.name), plainto_tsquery('simple', $search_str)) as rank
WHERE rank > 0
ORDER BY rank DESC;

/* SELECT19
 * Search results for users
 * Hundreds per day
 */
SELECT u."name", u.email, u.bio, u.join_date, u.expert, u.banned, p.path as photo_path
FROM "user" u INNER JOIN photo p ON u.photo_id = p.id, 
ts_rank_cd(to_tsvector(u.name), plainto_tsquery('simple', $search_str)) as rank
WHERE rank > 0
ORDER BY rank DESC;