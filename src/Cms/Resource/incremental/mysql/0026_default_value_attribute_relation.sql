ALTER TABLE `cms_attribute_relation`
ADD `cms_attribute_value_id` int(11) NULL AFTER `cms_attribute_id`,
ADD FOREIGN KEY (`cms_attribute_value_id`) REFERENCES `cms_attribute_value` (`id`) ON DELETE CASCADE;