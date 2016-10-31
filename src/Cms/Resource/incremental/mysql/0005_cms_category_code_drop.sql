ALTER TABLE `cms_category` DROP COLUMN `code`;
ALTER TABLE `cms_category`
ADD FOREIGN KEY (`parent_id`) REFERENCES `cms_category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;