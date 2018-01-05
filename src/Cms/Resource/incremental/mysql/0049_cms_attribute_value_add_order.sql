ALTER TABLE `cms_attribute_value`
ADD `order` int(11) NOT NULL DEFAULT '0' AFTER `label`;
ALTER TABLE `cms_attribute_value`
ADD INDEX `order` (`order`);