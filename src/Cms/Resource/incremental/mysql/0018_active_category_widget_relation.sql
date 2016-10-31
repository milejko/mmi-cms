ALTER TABLE `cms_category_widget_category`
ADD `active` tinyint(4) NOT NULL DEFAULT '0';

ALTER TABLE `cms_category_widget_category`
ADD INDEX `active` (`active`);