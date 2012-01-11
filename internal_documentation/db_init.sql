
CREATE DATABASE IF NOT EXISTS alumna
CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE USER 'alumnaAdmin'@'localhost' IDENTIFIED BY 'alumna%adm%orion';
GRANT DELETE, INSERT, SELECT, SHOW_VIEW, UPDATE ON alumna.*
    TO 'alumnaAdmin'@'localhost';

CREATE USER 'leffler'@'localhost' IDENTIFIED BY 'leffler';
GRANT SELECT ON alumna.* TO 'leffler'@'localhost';

USE alumna;

SOURCE 'iph_table.sql';

LOAD DATA INFILE 'iph_final.txt'
    INTO TABLE iph;

