ALTER TABLE `cms_cron`
ADD `lock` int(11) NOT NULL DEFAULT '0' AFTER `active`;
ALTER TABLE `cms_cron`
ADD INDEX `lock` (`lock`);