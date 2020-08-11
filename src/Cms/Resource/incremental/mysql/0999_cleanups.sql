SET @schemaname = (SELECT DATABASE());

SET @fkeyname = (SELECT CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    TABLE_SCHEMA = @schemaname AND
    TABLE_NAME = 'cms_category' AND
    REFERENCED_TABLE_NAME = 'cms_category_type' AND
    COLUMN_NAME = 'cms_category_type_id' LIMIT 1);

SET @queryStr = CONCAT('ALTER TABLE `cms_category` DROP FOREIGN KEY ', @fkeyname);
PREPARE qry from @queryStr;
EXECUTE qry;

SET @anotherfkeyname = (SELECT CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    TABLE_SCHEMA = @schemaname AND
    TABLE_NAME = 'cms_category_widget_category' AND
    REFERENCED_TABLE_NAME = 'cms_category_widget' AND
    COLUMN_NAME = 'cms_category_widget_id' LIMIT 1);

SET @anotherqueryStr = CONCAT('ALTER TABLE `cms_category_widget_category` DROP FOREIGN KEY ', @anotherfkeyname);
PREPARE anotherqry from @anotherqueryStr;
EXECUTE anotherqry;

ALTER TABLE `cms_category` DROP COLUMN `cms_category_type_id`;
ALTER TABLE `cms_category_widget_category` DROP COLUMN `cms_category_widget_id`;

DROP TABLE IF EXISTS `cms_attribute_value_relation`;
DROP TABLE IF EXISTS `cms_attribute_relation`;
DROP TABLE IF EXISTS `cms_attribute_value`;
DROP TABLE IF EXISTS `cms_attribute`;
DROP TABLE IF EXISTS `cms_attribute_type`;

DROP TABLE IF EXISTS `cms_category_widget_section`;
DROP TABLE IF EXISTS `cms_category_widget`;
DROP TABLE IF EXISTS `cms_category_section`;
DROP TABLE IF EXISTS `cms_category_type`;
