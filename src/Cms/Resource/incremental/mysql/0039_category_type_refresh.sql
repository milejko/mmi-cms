ALTER TABLE `cms_category`
CHANGE `cacheLifetime` `cacheLifetime` int(11) NULL DEFAULT NULL AFTER `dateModify`;

ALTER TABLE `cms_category_type`
ADD `cacheLifetime` int NOT NULL DEFAULT '2592000'  AFTER `mvcParams`;

UPDATE `cms_category` SET `cacheLifetime` = NULL WHERE `cacheLifetime` = '2592000';