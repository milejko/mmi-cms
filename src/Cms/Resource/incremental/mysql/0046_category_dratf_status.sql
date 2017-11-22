ALTER TABLE `cms_category`
ADD `status` tinyint(4) NOT NULL DEFAULT 0 AFTER `cms_category_original_id`;

UPDATE `cms_category` SET `status` = 10;

ALTER TABLE `cms_category`
ADD INDEX `status` (`status`);