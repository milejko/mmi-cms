ALTER TABLE `cms_category_type`
CHANGE `template` `mvcParams` varchar(128) COLLATE 'utf8_polish_ci' NOT NULL AFTER `key`;