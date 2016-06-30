ALTER TABLE `cms_category`
CHANGE `uri` `uri` varchar(255) COLLATE 'utf8_polish_ci' NOT NULL AFTER `description`;

ALTER TABLE `cms_article` DROP COLUMN `object`;
ALTER TABLE `cms_article` DROP COLUMN `objectId`;