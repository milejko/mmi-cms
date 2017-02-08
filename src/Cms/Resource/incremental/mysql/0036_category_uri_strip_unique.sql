ALTER TABLE `cms_category`
ADD INDEX `uri` (`uri`),
DROP INDEX `uri`;