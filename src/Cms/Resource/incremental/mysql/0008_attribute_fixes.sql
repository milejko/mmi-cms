ALTER TABLE `cms_attribute_group_attribute`
ADD `required` tinyint NOT NULL DEFAULT '0',
ADD `active` tinyint NOT NULL DEFAULT '1' AFTER `required`;

ALTER TABLE `cms_attribute_group_attribute`
ADD INDEX `active` (`active`);

ALTER TABLE `cms_attribute` DROP COLUMN `active`;