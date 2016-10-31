ALTER TABLE `cms_category`
ADD `redirectUri` varchar(255) COLLATE 'utf8_polish_ci' NULL AFTER `customUri`,
ADD `mvcParams` text COLLATE 'utf8_polish_ci' NULL AFTER `redirectUri`;