DROP TABLE IF EXISTS `cms_attribute_value_relation`;
DROP TABLE IF EXISTS `cms_attribute_relation`;
DROP TABLE IF EXISTS `cms_attribute_value`;
DROP TABLE IF EXISTS `cms_attribute`;
DROP TABLE IF EXISTS `cms_attribute_type`;

ALTER TABLE `cms_category`
DROP FOREIGN KEY IF EXISTS `cms_category_type_id_ibfk_1`;
ALTER TABLE `cms_category`
DROP FOREIGN KEY IF EXISTS `cms_category_type_id_ibfk_2`;

ALTER TABLE `cms_category` DROP COLUMN IF EXISTS `cms_category_type_id`;

ALTER TABLE `cms_category_widget_category`
DROP FOREIGN KEY IF EXISTS `cms_category_widget_category_ibfk_1`;
ALTER TABLE `cms_category_widget_category`
DROP FOREIGN KEY IF EXISTS `cms_category_widget_category_ibfk_2`;
ALTER TABLE `cms_category_widget_category` DROP COLUMN IF EXISTS `cms_category_widget_id`;

DROP TABLE IF EXISTS `cms_category_widget_section`;
DROP TABLE IF EXISTS `cms_category_widget`;
DROP TABLE IF EXISTS `cms_category_section`;
DROP TABLE IF EXISTS `cms_category_type`;
