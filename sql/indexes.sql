-----------------------------
-- PERFORMANCE
-----------------------------

/*
* Queries that search for questions, answers and comments ( any content ) 
    of a given user are used often and need to be fast.
    Clustering will improve performance of fetching all the content
    of a given user, which represents medium cardinality.
    There is no need for range search or sorting. 
*

| **Relation**        | content                                |
| **Attribute**       | author_id                              |
| **Type**            | Hash                                   |
| **Cardinality**     | Medium                                 |
| **Clustering**      | Yes                                    |
*/
CREATE INDEX content_author ON content USING hash (author_id);


/*
* Presenting trending questions and search results imply the need of sorting
  and fetching them by range. As such we chose to use B-Tree indexes.
*
| **Relation**        | content                          |
| **Attribute**       | creation_date                    |
| **Type**            | B-tree                           |
| **Cardinality**     | High                             |
| **Clustering**      | No                               |
*/
CREATE INDEX content_dates ON content USING btree (creation_date);

/*
* The notification table is densely populated and very often queried. 
  Query results must be ordered by date. Since cardinality is High, it's not a good clustering candidate. 
*
| **Relation**        | notification                    |
| **Attribute**       | date                            |
| **Type**            | B-tree                          |
| **Cardinality**     | High                            |
| **Clustering**      | No                              |
*/
CREATE INDEX notification_date ON notification USING btree (date);

/*
* The notification table is very large and very often queried. Query results must be ordered by the date. 
 Cardinality is medium and the user id is the main attribute to look for, so it's a great candidate for clustering. 
*
| **Relation**        | notification                     |
| **Attribute**       | user_id                          |
| **Type**            | B-tree                           |
| **Cardinality**     | High                             |
| **Clustering**      | No                               |
*/
CREATE INDEX notification_user_id ON notification USING btree (user_id);


-----------------------------
-- FULL-TEXT SEARCH
-----------------------------

/*
* (FTS) To speed up the searching process for content. GiST is used because 
  question fields may be changed frequently (specially the title and body),
  and GiST is better for dynamic data and faster for updates.
*
| **Relation**        | question            |
| **Attribute**       | search              |
| **Type**            | GIST                |
| **Clustering**      | No                  |
*/
CREATE INDEX question_search ON question USING GIST (search); 


/*
* (FTS) To speed up the search for tags by their name. GIN is used because the
  name of a tag is never changed and this type of index is faster 
  for static data. 
*
| **Relation**        | tag                 |
| **Attribute**       | search              |
| **Type**            | GIN                 |
| **Clustering**      | No                  |
*/
CREATE INDEX tag_search ON tag USING GIN (search);

/*
* (FTS) To speed up the search for users by their name. GiST is used because the 
name of a user may be changed, even though it's not very frequent. GiST is more suited for dynamic data 
and faster for updates.
*
| **Relation**        | user                |
| **Attribute**       | search              |
| **Type**            | GIN                 |
| **Clustering**      | No                  |
*/
CREATE INDEX user_search ON "user" USING GIST (search);