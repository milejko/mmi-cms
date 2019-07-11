ALTER TABLE `cms_category_widget_category`
ADD `uuid` varchar(40) NULL AFTER `id`;

UPDATE `cms_category_widget_category` SET `uuid` = uuid();

ALTER TABLE `cms_category_widget_category`
    CHANGE `uuid` `uuid` varchar(40) COLLATE 'utf8_polish_ci' NOT NULL AFTER `id`;