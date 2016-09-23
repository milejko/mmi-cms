CREATE TABLE cms_tag
(
  `id` integer NOT NULL AUTO_INCREMENT,
  tag varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE cms_tag_relation
(
  `id` integer NOT NULL AUTO_INCREMENT,
  cms_tag_id integer NOT NULL,
  `object` varchar(64) NOT NULL,
  `objectId` integer NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cms_tag_relation_ibfk_1` (`cms_tag_id`),
  KEY `object_object_id` (`object`,	`objectId`),
  CONSTRAINT `cms_tag_relation_ibfk_1` FOREIGN KEY (cms_tag_id) REFERENCES cms_tag(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
