ALTER TABLE `cms_file` ADD COLUMN `cms_file_original_id` int(11) DEFAULT NULL AFTER `active`;
ALTER TABLE `cms_file` ADD COLUMN `newUploaded` tinyint(4) DEFAULT '0' AFTER `cms_file_original_id`;
ALTER TABLE `cms_file` ADD CONSTRAINT `cms_file_ibfk_1` FOREIGN KEY (`cms_file_original_id`) REFERENCES `cms_file` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE `cms_file` ADD KEY `newUploaded` (`newUploaded`);