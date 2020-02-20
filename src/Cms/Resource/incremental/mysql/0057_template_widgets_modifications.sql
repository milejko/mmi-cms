ALTER TABLE `cms_category`
ADD `template` varchar(255) COLLATE 'utf8_polish_ci' NULL DEFAULT '' AFTER `cms_auth_id`;

ALTER TABLE `cms_category`
ADD INDEX `template` (`template`);

ALTER TABLE `cms_category_widget_category`
CHANGE `cms_category_widget_id` `cms_category_widget_id` int(11) NULL AFTER `uuid`;

ALTER TABLE `cms_category_widget_category`
ADD `widget` varchar(255) COLLATE 'utf8_polish_ci' NULL DEFAULT '' AFTER `uuid`;

ALTER TABLE `cms_category_widget_category`
ADD `section` varchar(255) COLLATE 'utf8_polish_ci' NULL DEFAULT '' AFTER `widget`;