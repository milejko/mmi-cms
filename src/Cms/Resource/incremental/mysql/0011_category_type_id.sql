ALTER TABLE `cms_category`
ADD COLUMN `cms_category_type_id` int(11) NULL AFTER `id`,
ADD CONSTRAINT `cms_category_type_id_ibfk_2` FOREIGN KEY (`cms_category_type_id`) REFERENCES `cms_category_type` (`id`) ON DELETE SET NULL;