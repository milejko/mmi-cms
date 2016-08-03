ALTER TABLE `cms_attribute_value`
ADD INDEX `value` (`value`(128));

DROP TABLE IF EXISTS `cms_attribute_group_relation`;
DROP TABLE IF EXISTS `cms_attribute_group_attribute`;
DROP TABLE IF EXISTS `cms_attribute_group`;

CREATE TABLE `cms_attribute_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_attribute_id` int(11) NOT NULL,
  `object` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `objectId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cms_attribute_relation_ibfk_1` (`cms_attribute_id`),
  KEY `object_object_id` (`object`,`objectId`),
  CONSTRAINT `cms_attribute_relation_ibfk_1` FOREIGN KEY (`cms_attribute_id`) REFERENCES `cms_attribute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;