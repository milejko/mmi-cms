ALTER TABLE `cms_category`
ADD `cms_category_type_id` int(11) NULL AFTER `id`,
ADD FOREIGN KEY (`cms_category_type_id`) REFERENCES `cms_category_type` (`id`) ON DELETE SET NULL;