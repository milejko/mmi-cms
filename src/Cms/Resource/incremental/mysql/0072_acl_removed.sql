ALTER TABLE `cms_auth`
ADD `roles` text NULL AFTER `password`;

DROP TABLE IF EXISTS `cms_category_acl_backup`;
CREATE TABLE `cms_category_acl_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_role_id` int(11),
  `cms_category_id` int(11) NOT NULL,
  `access` varchar(8) DEFAULT 'deny',
  PRIMARY KEY (`id`)
);
INSERT INTO `cms_category_acl_backup` SELECT * FROM `cms_category_acl`;
ALTER TABLE `cms_category_acl_backup` ADD COLUMN `role` varchar(128) AFTER `id`;
UPDATE `cms_category_acl_backup` SET `role` = (SELECT `cr`.`name` from `cms_role` AS `cr` WHERE `cr`.`id` = `cms_category_acl_backup`.`cms_role_id`);
ALTER TABLE `cms_category_acl_backup` DROP COLUMN `cms_role_id`;

DROP TABLE IF EXISTS `cms_category_acl`;
CREATE TABLE `cms_category_acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(128) NOT NULL DEFAULT 'guest',
  `cms_category_id` int(11) NOT NULL,
  `access` varchar(8) DEFAULT 'deny',
  PRIMARY KEY (`id`),
  KEY `access` (`access`),
  KEY `cms_category_id` (`cms_category_id`),
  KEY `role` (`role`),
  CONSTRAINT `cms_category_acl_ibfk_1` FOREIGN KEY (`cms_category_id`) REFERENCES `cms_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO `cms_category_acl` SELECT * FROM `cms_category_acl_backup`;
DROP TABLE `cms_category_acl_backup`;

UPDATE `cms_auth` SET `roles` = (SELECT GROUP_CONCAT(`cms_role`.`name`)
FROM `cms_auth_role` JOIN `cms_role` ON `cms_role`.`id` = `cms_auth_role`.`cms_role_id`
WHERE `cms_auth_role`.`cms_auth_id` = `cms_auth`.`id`
GROUP BY `cms_auth_role`.`cms_auth_id`);

DROP TABLE `cms_auth_role`;
DROP TABLE `cms_acl`;
DROP TABLE `cms_role`;