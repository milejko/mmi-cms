CREATE TABLE `cms_banner` (
    `id` integer NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
	`key` varchar(128) NOT NULL,
    `dateAdd` DATETIME NOT NULL,
    `active` TINYINT DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE `key` (`key`),
	KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
