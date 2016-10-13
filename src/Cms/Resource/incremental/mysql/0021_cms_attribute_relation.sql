ALTER TABLE `cms_attribute_relation`
ADD `order` int(11) NOT NULL DEFAULT '0' AFTER `objectId`,
DROP `order`;
ALTER TABLE `cms_attribute_relation`
ADD INDEX `order` (`order`);