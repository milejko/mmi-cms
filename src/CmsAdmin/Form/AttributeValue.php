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
 * Formularz wartości atrybutu
 */
class AttributeValue extends \Mmi\Form\Form {

	public function init() {

		//wartość
		$this->addElementText('value')
			->setLabel('wartość')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(1, 1024);

		//labelka
		$this->addElementText('label')
			->setLabel('etykieta')
			->addFilterStringTrim()
			->addFilterEmptyToNull()
			->addValidatorStringLength(1, 64);

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz wartość');
	}

	/**
	 * Przed zapisem
	 * @return boolean
	 */
	public function beforeSave() {
		//labelka jest podana - nic do zrobioenia
		if ($this->getElement('label')->getValue()) {
			return true;
		}
		//podstawianie wartości za labelkę
		$this->getRecord()->label = mb_substr($this->getElement('value')->getValue(), 0, 64);
		return true;
	}

}
