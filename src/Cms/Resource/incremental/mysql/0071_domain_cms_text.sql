ALTER TABLE `cms_text`
ADD `template` varchar(255) COLLATE 'utf8_polish_ci' NULL DEFAULT NULL AFTER `id`;
DROP INDEX `cms_text_lang_key` ON `cms_text`;
ALTER TABLE `cms_text` ADD UNIQUE `cms_text_lang_template_key` (`lang`, `template`, `key`);

ALTER TABLE `cms_tag`
ADD `template` varchar(255) COLLATE 'utf8_polish_ci' NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `cms_tag`
ADD `lang` varchar(2) COLLATE 'utf8_polish_ci' DEFAULT NULL AFTER `template`;
DROP INDEX `tag` ON `cms_tag`;

ALTER TABLE `cms_tag` ADD UNIQUE `cms_tag_lang_template_tag` (`lang`, `template`, `tag`);