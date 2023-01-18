ALTER TABLE `cms_auth`
ADD `roles` text NULL AFTER `password`;

ALTER TABLE `cms_category_acl`
ADD `role` varchar(128) NOT NULL DEFAULT 'guest' AFTER `id`;

ALTER TABLE `cms_category_acl`
ADD INDEX `role` (`role`);

ALTER TABLE `cms_category_acl`
DROP FOREIGN KEY `cms_category_acl_ibfk_3`;

UPDATE `cms_auth` SET `roles` = (SELECT GROUP_CONCAT(`cms_role`.`name`)
FROM `cms_auth_role` JOIN `cms_role` ON `cms_role`.`id` = `cms_auth_role`.`cms_role_id`
WHERE `cms_auth_role`.`cms_auth_id` = `cms_auth`.`id`
GROUP BY `cms_auth_role`.`cms_auth_id`);

UPDATE `cms_category_acl` SET `role` = (SELECT `cr`.`name` from `cms_role` AS `cr` WHERE `cr`.`id` = `cms_category_acl`.`cms_role_id`);

ALTER TABLE `cms_category_acl` DROP `cms_role_id`;
DROP TABLE `cms_auth_role`;
DROP TABLE `cms_acl`;
DROP TABLE `cms_role`;