CREATE DATABASE IF NOT EXISTS vibe16;
USE vibe16;

SET NAMES utf8;

DROP TABLE IF EXISTS users;
CREATE TABLE users
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) DEFAULT NULL,
    lastname VARCHAR(100) DEFAULT NULL

    -- ADDED UNIQUE KEY TO EMAIL IN KMOM06
    -- UNIQUE KEY `email_unique` (`email`)
) ENGINE INNODB CHARACTER SET utf8;

DELETE FROM users;
INSERT INTO users (username, email, password) VALUES
    ('Admin', 'admin@admin.com', '$2y$10$Ht9fFpwf73QWi5pEAH/F/e/a2oiTSFN7S.//7jxpMYIuj.EFIjLKe')
;
SELECT * FROM users;
