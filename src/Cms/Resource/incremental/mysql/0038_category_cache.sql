ALTER TABLE `cms_category`
ADD `cacheLifetime` int NOT NULL DEFAULT '2592000'  AFTER `dateModify`;