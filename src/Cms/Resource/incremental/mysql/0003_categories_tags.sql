RENAME TABLE cms_tag_link TO cms_tag_relation;
drop table IF EXISTS `cms_news`;

CREATE TABLE `cms_article_type` (
    `id` integer NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `cms_article`
ADD `cms_article_type_id` int(11) NULL AFTER `id`,
ADD FOREIGN KEY (`cms_article_type_id`) REFERENCES `cms_article_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `cms_article` DROP COLUMN `noindex`;

ALTER TABLE `cms_article`
ADD `lead` text AFTER `dateModify`;

ALTER TABLE `cms_article`
ADD `object` varchar(32) AFTER `dateModify`,
ADD `objectId` integer AFTER `object`;

ALTER TABLE `cms_article`
ADD INDEX `object_object_id` (`object`, `objectId`);

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

CREATE TABLE `cms_category_relation` (
    id integer NOT NULL AUTO_INCREMENT,
    `cms_category_id` integer NOT NULL,
	`object` varchar(32) NOT NULL,
	`objectId` integer,
	PRIMARY KEY (`id`),
	CONSTRAINT `cms_article_category_ibfk_1` FOREIGN KEY (`cms_category_id`) REFERENCES `cms_category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `object_object_id` (`object`,	`objectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;