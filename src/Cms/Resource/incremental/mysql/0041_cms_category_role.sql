CREATE TABLE `cms_category_role` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_role_id` integer NOT NULL,
  `cms_category_id` integer NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `cms_category_role_ibfk_1` FOREIGN KEY (`cms_role_id`) REFERENCES `cms_role` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `cms_category_role_ibfk_2` FOREIGN KEY (`cms_category_id`) REFERENCES `cms_category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;