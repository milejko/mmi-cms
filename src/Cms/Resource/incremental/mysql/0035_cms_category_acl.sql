CREATE TABLE `cms_category_acl` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_role_id` integer NOT NULL,
  `cms_category_id` integer NOT NULL,
  `access` varchar(8) COLLATE utf8_polish_ci DEFAULT 'deny',
  PRIMARY KEY (`id`),
  KEY `access` (`access`),
  CONSTRAINT `cms_category_acl_ibfk_1` FOREIGN KEY (`cms_role_id`) REFERENCES `cms_role` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `cms_category_acl_ibfk_2` FOREIGN KEY (`cms_category_id`) REFERENCES `cms_category` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;