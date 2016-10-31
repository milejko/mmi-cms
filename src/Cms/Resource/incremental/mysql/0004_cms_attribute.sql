CREATE TABLE `cms_attribute` (
    `id` integer NOT NULL AUTO_INCREMENT,
	`lang` varchar(2)  DEFAULT NULL,
    `name` varchar(128) NOT NULL,
	`key` varchar(128) NOT NULL,
    `description` text,
    `fieldClass` varchar(64) NOT NULL,
    `filterClasses` text NOT NULL,
    `validatorClasses` text NOT NULL,
	`multiple` TINYINT DEFAULT 0 NOT NULL,
    `indexWeight` integer NOT NULL DEFAULT 0,
    `active` TINYINT DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `key` (`key`),
	KEY `lang` (`lang`),
	KEY `name` (`name`),
	KEY `indexWeight` (`indexWeight`),
	KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_attribute_value` (
    `id` integer NOT NULL AUTO_INCREMENT,
    `cms_attribute_id` integer NOT NULL,
    `value` text NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `cms_attribute_value_ibfk_1` FOREIGN KEY (`cms_attribute_id`) REFERENCES `cms_attribute` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_attribute_value_relation` (
    id integer NOT NULL AUTO_INCREMENT,
    `cms_attribute_value_id` integer NOT NULL,
	`object` varchar(32) NOT NULL,
	`objectId` integer,
	PRIMARY KEY (`id`),
	CONSTRAINT `cms_attribute_value_relation_ibfk_1` FOREIGN KEY (`cms_attribute_value_id`) REFERENCES `cms_attribute_value` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `object_object_id` (`object`, `objectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_attribute_group` (
    `id` integer NOT NULL AUTO_INCREMENT,
	`lang` varchar(2)  DEFAULT NULL,
    `name` varchar(128) NOT NULL,
	`key` varchar(128) NOT NULL,
    `description` text,
	PRIMARY KEY (`id`),
	UNIQUE `key` (`key`),
	KEY `lang` (`lang`),
	KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_attribute_group_attribute` (
    `id` integer NOT NULL AUTO_INCREMENT,
    `cms_attribute_id` integer NOT NULL,
    `cms_attribute_group_id` integer NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `cms_attribute_group_attribute_ibfk_1` FOREIGN KEY (`cms_attribute_id`) REFERENCES `cms_attribute` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `cms_attribute_group_attribute_ibfk_2` FOREIGN KEY (`cms_attribute_group_id`) REFERENCES `cms_attribute_group` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_attribute_group_relation` (
    id integer NOT NULL AUTO_INCREMENT,
    `cms_attribute_group_id` integer NOT NULL,
	`object` varchar(32) NOT NULL,
	`objectId` integer,
	PRIMARY KEY (`id`),
	CONSTRAINT `cms_attribute_group_relation_ibfk_1` FOREIGN KEY (`cms_attribute_group_id`) REFERENCES `cms_attribute_group` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `object_object_id` (`object`, `objectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
