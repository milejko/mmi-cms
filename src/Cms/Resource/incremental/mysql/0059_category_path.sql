ALTER TABLE `cms_category` ADD COLUMN `path` varchar(500) AFTER `uri`;
ALTER TABLE `cms_category`ADD KEY (`path`);