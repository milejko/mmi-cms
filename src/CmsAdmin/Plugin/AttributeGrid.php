<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid atrybutów
 */
class AttributeGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(new \Cms\Orm\CmsAttributeQuery);

		//nazwa atrybutu
		$this->addColumnText('name')
			->setLabel('nazwa');

		//klucz atrybutu
		$this->addColumnText('key')
			->setLabel('klucz');

		//opis
		$this->addColumnText('description')
			->setLabel('opis');

		//klasa pola
		$this->addColumnSelect('fieldClass')
			->setLabel('klasa pola')
			->setMultioptions([
				'\Mmi\Form\Element\Text' => 'tekst',
				'\Mmi\Form\Element\Textarea' => 'długi tekst',
				'\Cms\Form\Element\TinyMce' => 'edytor WYSIWYG',
				'\Cms\Form\Element\DatePicker' => 'data',
				'\Cms\Form\Element\DateTimePicker' => 'data i czas',
				'\Cms\Form\Element\Plupload' => 'wgrywarka plików',
				'\Mmi\Form\Element\Select' => 'wybór jednokrotny',
				'\Mmi\Form\Element\MultiCheckbox' => 'wybór wielokrotny',
		]);

		//waga
		$this->addColumnText('indexWeight')
			->setLabel('waga w indeksie');

		//wymagany
		$this->addColumnCheckbox('required')
			->setLabel('wymagany');

		//unikalny
		$this->addColumnCheckbox('unique')
			->setLabel('unikalny');

		//zmaterializowany
		$this->addColumnCheckbox('materialized')
			->setLabel('zmaterializowany');

		//operacje
		$this->addColumnOperation();
	}

}
