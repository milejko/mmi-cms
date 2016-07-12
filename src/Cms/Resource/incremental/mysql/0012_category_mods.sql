ALTER TABLE `cms_category`
CHANGE `name` `name` varchar(128) COLLATE 'utf8_polish_ci' NULL AFTER `lang`,
CHANGE `title` `title` varchar(128) COLLATE 'utf8_polish_ci' NULL AFTER `name`,
CHANGE `lead` `lead` text COLLATE 'utf8_polish_ci' NULL AFTER `title`,
CHANGE `text` `text` text COLLATE 'utf8_polish_ci' NULL AFTER `lead`;

ALTER TABLE `cms_category`
ADD `customUri` varchar(255) COLLATE 'utf8_polish_ci' NULL AFTER `uri`;

ALTER TABLE `cms_category`
ADD UNIQUE `uri` (`uri`),
DROP INDEX `uri`;

ALTER TABLE `cms_category`
ADD UNIQUE `customUri` (`customUri`);