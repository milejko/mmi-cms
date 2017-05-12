ALTER TABLE `cms_category_acl`
DROP FOREIGN KEY `cms_category_acl_ibfk_1`,
ADD FOREIGN KEY (`cms_role_id`) REFERENCES `cms_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cms_category_acl`
DROP FOREIGN KEY `cms_category_acl_ibfk_2`,
ADD FOREIGN KEY (`cms_category_id`) REFERENCES `cms_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
