drop table IF EXISTS `cms_tag_link`;
drop table IF EXISTS `cms_news`;
ALTER TABLE `cms_article`
CHANGE `noindex` `index` tinyint(4) NOT NULL DEFAULT '1' AFTER `text`;

UPDATE `cms_article` SET `index` = 2 WHERE `index` = 0;
UPDATE `cms_article` SET `index` = 0 WHERE `index` = 1;
UPDATE `cms_article` SET `index` = 1 WHERE `index` = 2;

ALTER TABLE `cms_article`
ADD `lead` text AFTER `text`;

ALTER TABLE `cms_article`
ADD `active` tinyint(4) NOT NULL DEFAULT '0';

ALTER TABLE `cms_article`
ADD INDEX `active` (`active`);

UPDATE `cms_article` SET `active` = 1;

CREATE TABLE `cms_category` (
    `id` integer NOT NULL AUTO_INCREMENT,
	`lang` varchar(2)  DEFAULT NULL,
    `name` varchar(128) NOT NULL,
    `description` text,
    `uri` varchar(128) NOT NULL,
    `code` varchar(128) NOT NULL,
	`parent_id` INTEGER,
	`order` integer DEFAULT 0 NOT NULL,
    `dateAdd` DATETIME NOT NULL,
    `dateModify` DATETIME,
    `active` TINYINT DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `code` (`code`),
	KEY `name` (`name`),
	KEY `uri` (`uri`),
	KEY `lang` (`lang`),
	KEY `dateAdd` (`dateAdd`),
	KEY `dateModify` (`dateModify`),
	KEY `active` (`active`),
	KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_article_category` (
    id integer NOT NULL AUTO_INCREMENT,
    `cms_article_id` integer NOT NULL,
    `cms_category_id` integer NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `cms_article_category_ibfk_1` FOREIGN KEY (`cms_article_id`) REFERENCES `cms_article` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `cms_article_category_ibfk_2` FOREIGN KEY (`cms_category_id`) REFERENCES `cms_category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_article_tag (
    id integer NOT NULL AUTO_INCREMENT,
    cms_article_id integer NOT NULL,
    cms_tag_id integer NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `cms_article_tag_ibfk_1` FOREIGN KEY (`cms_article_id`) REFERENCES `cms_article` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `cms_article_tag_ibfk_2` FOREIGN KEY (`cms_tag_id`) REFERENCES `cms_tag` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_block` (
    `id` integer NOT NULL AUTO_INCREMENT,
	`lang` varchar(2)  DEFAULT NULL,
    `title` varchar(160) NOT NULL,
    `lead` text,
	`text` text,
	`object` varchar(32) NOT NULL,
	`objectId` integer,
    `dateAdd` DATETIME NOT NULL,
    `dateModify` DATETIME,
	PRIMARY KEY (`id`),
	KEY `object` (`object`, `objectId`),
	KEY `lang` (`lang`),
	KEY `title` (`title`),
	KEY `dateAdd` (`dateAdd`),
	KEY `dateModify` (`dateModify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
