ALTER TABLE `cms_file` DROP FOREIGN KEY `cms_file_ibfk_1`;
ALTER TABLE `cms_file` DROP COLUMN `cms_file_original_id`;
ALTER TABLE `cms_file` DROP COLUMN `newUploaded`;
DROP TABLE `cms_comment`;