ALTER TABLE `cms_file`
DROP INDEX `object`,
ADD INDEX `object` (`object`),
ADD INDEX `objectId` (`objectId`);