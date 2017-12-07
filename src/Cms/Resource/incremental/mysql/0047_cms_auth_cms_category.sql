ALTER TABLE `cms_category` ADD COLUMN `cms_auth_id` integer DEFAULT NULL AFTER `id`;
ALTER TABLE `cms_category` ADD CONSTRAINT `cms_category_cms_auth_ibfk_1` FOREIGN KEY (`cms_auth_id`) REFERENCES `cms_auth` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
