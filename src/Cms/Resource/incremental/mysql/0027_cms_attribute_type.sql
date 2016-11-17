CREATE TABLE `cms_attribute_type` (
    `id` integer NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
    `fieldClass` varchar(64) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

INSERT INTO `cms_attribute_type` (`id`, `name`, `fieldClass`) VALUES
(1, 'data', '\Cms\Form\Element\DatePicker'),
(2, 'data i czas', '\Cms\Form\Element\DateTimePicker'),
(3, 'tagi', '\Cms\Form\Element\Tags'),
(4, 'tekst (jedna linijka)', '\Mmi\Form\Element\Text'),
(5, 'tekst (wiele linii)', '\Mmi\Form\Element\Textarea'),
(6, 'tekst z HTML (wysiwyg)', '\Cms\Form\Element\TinyMce'),
(7, 'wgrywarka pliku (pojedynczego)', '\Mmi\Form\Element\File'),
(8, 'wgrywarka wielu plików', '\Cms\Form\Element\Plupload'),
(9, 'wybór tak/nie (checkbox)', '\Mmi\Form\Element\Checkbox'),
(10, 'wybór pojedynczy (select)', '\Mmi\Form\Element\Select'),
(11, 'wybór wielokrotny (wiele checkboxów)', '\Mmi\Form\Element\MultiCheckbox');

ALTER TABLE `cms_attribute` ADD `cms_attribute_type_id` int(11) NOT NULL AFTER `id`;

UPDATE `cms_attribute` SET `cms_attribute_type_id` = 5;

ALTER TABLE `cms_attribute` ADD FOREIGN KEY (`cms_attribute_type_id`) REFERENCES `cms_attribute_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `cms_attribute` DROP COLUMN `fieldClass`;