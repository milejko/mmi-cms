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
				'\Mmi\Form\Element\Text' => 'Tekst',
				'\Mmi\Form\Element\Textarea' => 'Długi tekst',
				'\Cms\Form\Element\TinyMce' => 'Edytor WYSIWYG',
				'\Cms\Form\Element\DatePicker' => 'Data',
				'\Cms\Form\Element\DateTimePicker' => 'Data i czas',
				'\Cms\Form\Element\Plupload' => 'Wgrywarka plików',
				'\Mmi\Form\Element\Select' => 'Wybór jednokrotny',
				'\Mmi\Form\Element\MultiCheckbox' => 'Wybór wielokrotny',
		]);
		
		//@TODO filtry
		//$this->addElementMultiCheckbox('filterClasses')
		//	->setLabel('filtry')
		//	->setMultioptions([
		//]);
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

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

}
