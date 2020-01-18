CREATE TABLE `cms_category_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_type_id` int(11) NOT NULL,
  `key` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `required` tinyint(1) NOT NULL NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY (`name`),
  KEY (`key`),
  CONSTRAINT `cms_category_section_ibfk_1` FOREIGN KEY (`category_type_id`) REFERENCES `cms_category_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `cms_category_widget_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_category_widget_id` int(11) NOT NULL,
  `cms_category_section_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `cms_category_widget_section_ibfk_1` FOREIGN KEY (`cms_category_section_id`) REFERENCES `cms_category_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cms_category_widget_section_ibfk_2` FOREIGN KEY (`cms_category_widget_id`) REFERENCES `cms_category_widget` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `cms_category_widget_section_widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cms_category_widget_section_id` int(11) NOT NULL,
  `cms_category_widget_category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `cms_category_widget_section_widget_ibfk_1` FOREIGN KEY (`cms_category_widget_section_id`) REFERENCES `cms_category_widget_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cms_category_widget_section_widget_ibfk_2` FOREIGN KEY (`cms_category_widget_category_id`) REFERENCES `cms_category_widget_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
