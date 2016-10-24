ALTER TABLE `cms_attribute_value`
ADD `label` varchar(64) COLLATE 'utf8_polish_ci' NULL;

ALTER TABLE `cms_attribute_value`
ADD INDEX `label` (`label`);