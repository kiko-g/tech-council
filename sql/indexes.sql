/*
* Queries that search for posts, comments, replies ( any content ) 
    of a any user are used often and need to be fast.
    User’s may have a fair ammount of posts/comments/replies/content. 
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
CREATE INDEX content_author ON "content" USING hash (author_id);



/*
* Presenting trending questions and search results imply the need of sorting
  and fetching them by range. As such we chose to use B-Tree indexes. 
*
| **Relation**        | question                           |
| **Attribute**       | creation_date                         |
| **Type**            | B-Tree                                   |
| **Cardinality**     | Medium                                 |
| **Clustering**      | No                                    |
*/
CREATE INDEX question_dates ON question USING btree (creation_date);

/*
* To speed up the searching process for content. GiST is used because 
  question fields may be changed frequently (specially the title and body),
  and GiST is better for dynamic data and faster for updates.
*
| **Relation**        | question             |
| **Attribute**       | search              |
| **Type**            | GIST                |
| **Clustering**      | No                  |
*/
CREATE INDEX question_search ON question USING GIST (search); 


/*
* To speed up the search for tags by their name. GIN is used because the
  name of a tag is never changed and this type of index is better/faster 
  for static data. 
*
| **Relation**        | tag                 |
| **Attribute**       | search              |
| **Type**            | GIN                 |
| **Clustering**      | No                  |
*/
CREATE INDEX tag_search ON tag USING GIN (search);