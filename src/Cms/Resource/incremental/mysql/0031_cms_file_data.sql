ALTER TABLE `cms_file`
DROP INDEX `author`,
DROP INDEX `title`;

ALTER TABLE `cms_file` DROP COLUMN `author`;
ALTER TABLE `cms_file` DROP COLUMN `title`;
ALTER TABLE `cms_file` DROP COLUMN `source`;

ALTER TABLE `cms_file` ADD COLUMN `data` TEXT AFTER `original`;
