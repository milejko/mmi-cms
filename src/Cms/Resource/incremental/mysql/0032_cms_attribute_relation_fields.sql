ALTER TABLE `cms_attribute_relation`
ADD `filterClasses` text COLLATE 'utf8_polish_ci' NULL AFTER `cms_attribute_value_id`,
ADD `validatorClasses` text COLLATE 'utf8_polish_ci' NULL AFTER `filterClasses`,
ADD `required` tinyint NOT NULL DEFAULT '0' AFTER `validatorClasses`,
ADD `unique` tinyint NOT NULL DEFAULT '0' AFTER `required`,
ADD `materialized` tinyint NOT NULL DEFAULT '0' AFTER `unique`;