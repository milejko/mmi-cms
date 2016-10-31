ALTER TABLE `cms_category`
ADD `title` varchar(128) COLLATE 'utf8_polish_ci' NOT NULL AFTER `name`;

ALTER TABLE `cms_category`
ADD `lead` text COLLATE 'utf8_polish_ci' NOT NULL AFTER `title`;

ALTER TABLE `cms_category`
ADD `text` text COLLATE 'utf8_polish_ci' NOT NULL AFTER `lead`;

ALTER TABLE `cms_category`
ADD `https` tinyint(4) COLLATE 'utf8_polish_ci' AFTER `uri`;

ALTER TABLE `cms_category`
ADD `blank` tinyint(4) COLLATE 'utf8_polish_ci' NOT NULL DEFAULT 0 AFTER `https`;

ALTER TABLE `cms_category`
ADD `follow` tinyint(4) COLLATE 'utf8_polish_ci' NOT NULL DEFAULT 1 AFTER `blank`;
