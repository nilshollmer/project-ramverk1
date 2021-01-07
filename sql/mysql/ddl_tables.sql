--
-- Create database tables
-- Ramverk1 Project
--


SET NAMES 'utf8';

--
-- Drop tables
--
DROP TABLE IF EXISTS
    Question2Tag,
    Tag,
    Comment,
    Entry,
    Question,
    User
;

--
-- Table User
--
CREATE TABLE User
(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(80) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255),

    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL,
    `active` DATETIME DEFAULT NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;



--
-- Table Question
--
CREATE TABLE Question
(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user` VARCHAR(80) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `views` INTEGER DEFAULT 0,

    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL,

    FOREIGN KEY (user) REFERENCES User(username)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;



--
-- Table Entry
--
CREATE TABLE Entry
(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user` VARCHAR(80) NOT NULL,
    `question` INTEGER NOT NULL,
    `text` VARCHAR(255) NOT NULL,
    `score` INTEGER DEFAULT 0,

    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL,

    FOREIGN KEY (user) REFERENCES User(username),
    FOREIGN KEY (question) REFERENCES Question(id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;



--
-- Table Comment
--
CREATE TABLE Comment
(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user` VARCHAR(80) NOT NULL,
    `entry` INTEGER NOT NULL,
    `text` VARCHAR(255) NOT NULL,

    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL,

    FOREIGN KEY (user) REFERENCES User(username),
    FOREIGN KEY (entry) REFERENCES Entry(id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


--
-- Table Tag
--
CREATE TABLE Tag
(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(80) NOT NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


--
-- Table Question2Tag
--
CREATE TABLE Question2Tag
(
    `question_id` INTEGER NOT NULL,
    `tag_id` INTEGER NOT NULL,

    FOREIGN KEY (question_id) REFERENCES Question(id),
    FOREIGN KEY (tag_id) REFERENCES Tag(id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;
