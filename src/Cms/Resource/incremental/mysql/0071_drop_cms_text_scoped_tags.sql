DROP TABLE `cms_text`;
ALTER TABLE `cms_tag`
ADD `scope` varchar(255) COLLATE 'utf8_polish_ci' NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `cms_tag`
ADD `lang` varchar(2) COLLATE 'utf8_polish_ci' DEFAULT NULL AFTER `scope`;
DROP INDEX `tag` ON `cms_tag`;

ALTER TABLE `cms_tag` ADD UNIQUE `cms_tag_lang_scope_tag` (`lang`, `scope`, `tag`);