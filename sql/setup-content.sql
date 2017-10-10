USE vibe16;

SET NAMES utf8;

DROP TABLE IF EXISTS content;
CREATE TABLE content
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    path CHAR(120) UNIQUE,
    slug CHAR(120) UNIQUE,

    title VARCHAR(120),
    data TEXT,
    type CHAR(20),
    filter VARCHAR(80) DEFAULT 'bbcode',
    published DATETIME DEFAULT NULL,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated DATETIME DEFAULT NULL, --  ON UPDATE CURRENT_TIMESTAMP,
    deleted BOOLEAN NOT NULL DEFAULT 0

) ENGINE INNODB CHARACTER SET utf8;

DELETE FROM content;
INSERT INTO content (path, slug, title, type, published) VALUES
    ('home', 'home', 'Min första sida', 'page', '2017-10-05 15:05:25'),
    ('first-post', 'first-post', 'Mitt första inlägg', 'post', '2017-10-05 15:05:25')
;
SELECT * FROM content;
