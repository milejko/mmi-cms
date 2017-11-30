ALTER TABLE `cms_category`
ADD `cms_category_original_id` int(11) NULL AFTER `cms_category_type_id`;

ALTER TABLE `cms_category`
ADD FOREIGN KEY (`cms_category_original_id`) REFERENCES `cms_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cms_category`
ADD INDEX `customUri` (`customUri`),
DROP INDEX `customUri`;