--
-- Import from csv to tables
--
-- For project in Ramverk1
-- 2021-01-06
--

--
-- Empty tables before inserting
--

DELETE FROM Question2Tag;
DELETE FROM Tag;
DELETE FROM Comment;
DELETE FROM Entry;
DELETE FROM Question;
DELETE FROM User;



--
-- Enable LOAD DATA LOCAL INFILE on the server
--
SET GLOBAL local_infile = 1;
SHOW VARIABLES LIKE 'local_infile';


--
-- Insert data into tables
--

LOAD DATA LOCAL INFILE "sql/mysql/data/user.csv"
INTO TABLE User
CHARSET utf8
FIELDS
    TERMINATED BY ','
    ENCLOSED BY '"'
LINES
    TERMINATED BY '\n'
IGNORE 1 LINES
;

LOAD DATA LOCAL INFILE "sql/mysql/data/question.csv"
INTO TABLE Question
CHARSET utf8
FIELDS
    TERMINATED BY ','
    ENCLOSED BY '"'
LINES
    TERMINATED BY '\n'
IGNORE 1 LINES
;

LOAD DATA LOCAL INFILE "sql/mysql/data/entry.csv"
INTO TABLE Entry
CHARSET utf8
FIELDS
    TERMINATED BY ','
    ENCLOSED BY '"'
LINES
    TERMINATED BY '\n'
IGNORE 1 LINES
;

LOAD DATA LOCAL INFILE "sql/mysql/data/comment.csv"
INTO TABLE Comment
CHARSET utf8
FIELDS
    TERMINATED BY ','
    ENCLOSED BY '"'
LINES
    TERMINATED BY '\n'
IGNORE 1 LINES
;

LOAD DATA LOCAL INFILE "sql/mysql/data/tag.csv"
INTO TABLE Tag
CHARSET utf8
FIELDS
    TERMINATED BY ','
    ENCLOSED BY '"'
LINES
    TERMINATED BY '\n'
IGNORE 1 LINES
;

LOAD DATA LOCAL INFILE "sql/mysql/data/question2tag.csv"
INTO TABLE Question2Tag
CHARSET utf8
FIELDS
    TERMINATED BY ','
    ENCLOSED BY '"'
LINES
    TERMINATED BY '\n'
IGNORE 1 LINES
;
