CREATE TABLE `cms_attribute_type` (
    `id` integer NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
    `fieldClass` varchar(64) NOT NULL,
	`restricted` tinyint(1) NOT NULL DEFAULT 0,
	`multiple` tinyint(1) NOT NULL DEFAULT 0,
	`uploader` tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

INSERT INTO `cms_attribute_type` (`id`, `name`, `fieldClass`, `restricted`, `multiple`, `uploader`) VALUES
(1, 'data', '\Cms\Form\Element\DatePicker', 0, 0, 0),
(2, 'data i czas', '\Cms\Form\Element\DateTimePicker', 0, 0, 0),
(3, 'tagi', '\Cms\Form\Element\Tags', 1, 1, 0),
(4, 'tekst (jedna linijka)', '\Mmi\Form\Element\Text', 0, 0, 0),
(5, 'tekst (wiele linii)', '\Mmi\Form\Element\Textarea', 0, 0, 0),
(6, 'tekst z HTML (wysiwyg)', '\Cms\Form\Element\TinyMce', 0, 0, 0),
(7, 'wgrywarka pliku (pojedynczego)', '\Mmi\Form\Element\File', 0, 0, 1),
(8, 'wgrywarka wielu plików', '\Cms\Form\Element\Plupload', 0, 0, 1),
(9, 'wybór tak/nie (checkbox)', '\Mmi\Form\Element\Checkbox', 1, 0, 0),
(10, 'wybór pojedynczy (select)', '\Mmi\Form\Element\Select', 1, 0, 0),
(11, 'wybór wielokrotny (wiele checkboxów)', '\Mmi\Form\Element\MultiCheckbox', 1, 1, 0);

ALTER TABLE `cms_attribute` ADD `cms_attribute_type_id` int(11) NOT NULL AFTER `id`;

UPDATE `cms_attribute` SET `cms_attribute_type_id` = 5;

ALTER TABLE `cms_attribute` ADD FOREIGN KEY (`cms_attribute_type_id`) REFERENCES `cms_attribute_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `cms_attribute` DROP COLUMN `fieldClass`;