ALTER TABLE `cms_category_widget_category`
ADD `cms_category_section_id` int(11) NULL AFTER `cms_category_id`,
ADD FOREIGN KEY (`cms_category_section_id`) REFERENCES `cms_category_section` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

DROP TABLE `cms_category_widget_section_widget`;