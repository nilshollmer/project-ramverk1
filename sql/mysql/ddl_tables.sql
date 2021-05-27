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
    `email` VARCHAR(80) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `reputation` INTEGER DEFAULT 0,
    `location` VARCHAR(80),
    `occupation` VARCHAR(80),

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
    `text` TEXT NOT NULL,
    `views` INTEGER DEFAULT 0,
    `score` INTEGER DEFAULT 0,
    `entries` INTEGER DEFAULT 0,

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
    `text` TEXT NOT NULL,
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
    `question` INTEGER NOT NULL,
    `entry` INTEGER DEFAULT NULL,
    `text` TEXT NOT NULL,

    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL,

    FOREIGN KEY (user) REFERENCES User(username),
    FOREIGN KEY (question) REFERENCES Question(id),
    FOREIGN KEY (entry) REFERENCES Entry(id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;



--
-- Table Tag
--
CREATE TABLE Tag
(
    `name` VARCHAR(80) PRIMARY KEY NOT NULL,
    `description` TEXT
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;



--
-- Table Question2Tag
--
CREATE TABLE Question2Tag
(
    `question_id` INTEGER NOT NULL,
    `tag_name` VARCHAR(80) NOT NULL,

    FOREIGN KEY (question_id) REFERENCES Question(id),
    FOREIGN KEY (tag_name) REFERENCES Tag(name)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;
