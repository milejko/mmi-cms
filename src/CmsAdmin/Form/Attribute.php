<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz atrybutów
 */
class Attribute extends \Mmi\Form\Form {

	public function init() {

		//nazwa
		$this->addElementText('name')
			->setLabel('nazwa')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//opis
		$this->addElementTextarea('description')
			->setLabel('opis')
			->addFilterStringTrim();

		//opis
		$this->addElementSelect('fieldClass')
			->setLabel('pole formularza')
			->setRequired()
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

		//filtry
		$this->addElementSelect('filterClasses')
			->setLabel('filtry')
			->setMultioptions([
				null => '---',
				'\Mmi\Filter\StripTags' => 'usunięcie HTML',
				'\Mmi\Filter\StringTrim' => 'usunięcie spacji z początku i końca',
				'\Mmi\Filter\Url' => 'filtr do url\'a',
		]);

		//walidatory
		$this->addElementSelect('validatorClasses')
			->setLabel('walidator')
			->setMultioptions([
				null => '---',
				'\Mmi\Validator\Alnum' => 'alfanumeryczne',
				'\Mmi\Validator\EmailAddress' => 'e-mail',
				'\Mmi\Validator\Numeric' => 'numeryczne',
				'\Mmi\Validator\Integer' => 'liczby całkowite',
		]);

		//waga w indeksie
		$this->addElementText('indexWeight')
			->setLabel('waga w indeksie')
			->setDescription('0-1000, im wyższa waga, tym wyższa pozycja w wyszukiwarce, 0 oznacza brak w wynikach')
			->setValue(0)
			->addValidatorNumberBetween(0, 1000);

		//wymagany
		$this->addElementCheckbox('required')
			->setLabel('wymagany');

		//unikalny
		$this->addElementCheckbox('unique')
			->setLabel('unikalny');

		//zmaterializowany
		$this->addElementCheckbox('materialized')
			->setLabel('zmaterializowany');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

}
