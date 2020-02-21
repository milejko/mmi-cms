ALTER TABLE `cms_category_widget_category`
DROP FOREIGN KEY `cms_category_widget_category_ibfk_3`;
ALTER TABLE `cms_category_widget_category`
DROP `section`;
ALTER TABLE `cms_category_widget_category`
DROP `cms_category_section_id`;
DROP TABLE `cms_category_widget_section`;
DROP TABLE `cms_category_section`;
DROP TABLE `cms_category_relation`;