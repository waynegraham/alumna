
CREATE DATABASE IF NOT EXISTS alumna
CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE USER 'alumnaAdmin'@'localhost' IDENTIFIED BY 'alumna%adm%orion';
GRANT DELETE, INSERT, SELECT, UPDATE ON alumna.*
    TO 'alumnaAdmin'@'localhost';

CREATE USER 'leffler'@'localhost' IDENTIFIED BY 'leffler';
GRANT SELECT ON alumna.* TO 'leffler'@'localhost';

USE alumna;

--
-- First the IPH table.
--

SOURCE ./iph_table.sql;

-- If you have trouble with this command ("The used command is not allowed in
-- this MySQL version"), run it with "mysql --local_infile=1 ..."
LOAD DATA LOCAL INFILE 'iph_final.txt'
    INTO TABLE iph
    FIELDS TERMINATED BY '|'
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    ;

-- This is for a sanity check.
SELECT 'Imported with lastName';
SELECT COUNT(*) FROM iph WHERE lastname IS NOT NULL;
SELECT 'Imported without lastName';
SELECT COUNT(*) FROM iph WHERE lastname IS NULL;

--
-- Now the OpenResponses table
--

CREATE TABLE openresponses (
    openreponseid INTEGER PRIMARY KEY,
    accessionnumber INT,
    questionnumber INT,
    response TEXT,
    CONSTRAINT FOREIGN KEY (accessionnumber) REFERENCES iph (accessionNumber)
);
CREATE INDEX openresponses_idx
    ON openresponses (openreponseid, accessionnumber, questionnumber);

