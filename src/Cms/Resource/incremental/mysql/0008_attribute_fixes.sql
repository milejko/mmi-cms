ALTER TABLE `cms_attribute`
ADD `required` tinyint NOT NULL DEFAULT '0',
ADD `unique` tinyint NOT NULL DEFAULT '0' AFTER `required`,
ADD `materialized` tinyint NOT NULL DEFAULT '0' AFTER `unique`;

ALTER TABLE `cms_attribute`
CHANGE `filterClasses` `filterClasses` text COLLATE 'utf8_polish_ci' NULL AFTER `fieldClass`,
CHANGE `validatorClasses` `validatorClasses` text COLLATE 'utf8_polish_ci' NULL AFTER `filterClasses`;

ALTER TABLE `cms_attribute_group_attribute` 
ADD `active` tinyint NOT NULL DEFAULT '1',
ADD INDEX `active` (`active`);

ALTER TABLE `cms_attribute` DROP COLUMN `active`;
ALTER TABLE `cms_attribute` DROP COLUMN `multiple`;

ALTER TABLE `cms_attribute_group_relation`
CHANGE `object` `object` varchar(64) COLLATE 'utf8_polish_ci' NOT NULL AFTER `cms_attribute_group_id`;

ALTER TABLE `cms_attribute_value_relation`
CHANGE `object` `object` varchar(64) COLLATE 'utf8_polish_ci' NOT NULL AFTER `cms_attribute_value_id`;

ALTER TABLE `cms_category_relation`
CHANGE `object` `object` varchar(64) COLLATE 'utf8_polish_ci' NOT NULL AFTER `cms_category_id`;

ALTER TABLE `cms_file`
CHANGE `object` `object` varchar(64) COLLATE 'utf8_polish_ci' NULL AFTER `sticky`;
