CREATE TABLE `cms_category_widget` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `recordClass` varchar(64) COLLATE utf8_polish_ci,
  `formClass` varchar(64) COLLATE utf8_polish_ci,
  `mvcParams` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `mvcPreviewParams` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `cms_category_widget_category` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `cms_category_widget_id` integer NOT NULL,
  `cms_category_id` integer NOT NULL,
  `recordId` integer,
  `configJson` text,
  `order` integer NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`),
  CONSTRAINT `cms_category_widget_category_ibfk_1` FOREIGN KEY (`cms_category_widget_id`) REFERENCES `cms_category_widget` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `cms_category_widget_category_ibfk_2` FOREIGN KEY (`cms_category_id`) REFERENCES `cms_category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;