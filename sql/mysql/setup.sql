--
-- Create and choose database to use
-- For project in Ramverk1
-- 2021-01-06
--

CREATE DATABASE IF NOT EXISTS ramverk1_project;

USE ramverk1_project;


CREATE USER IF NOT EXISTS 'user'@'%'
    IDENTIFIED BY 'pass'
;

GRANT ALL PRIVILEGES
    ON ramverk1_project.*
    TO 'user'@'%'
;
