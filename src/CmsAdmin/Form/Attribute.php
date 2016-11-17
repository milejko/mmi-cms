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

		//klucz pola
		$this->addElementText('key')
			->setLabel('klucz')
			->addFilterAscii()
			->setRequired()
			->addValidatorAlnum('klucz może zawierać wyłącznie litery i cyfry')
			->addValidatorStringLength(2, 64)
			->addValidatorRecordUnique(new \Cms\Orm\CmsAttributeQuery, 'key', $this->getRecord()->id);
		
		//opis
		$this->addElementText('description')
			->setLabel('opis')
			->addFilterStringTrim();

		//pole formularza
		$this->addElementSelect('cmsAttributeType')
			->setLabel('pole formularza')
			->setRequired()
			->addValidatorNotEmpty()
			->setMultioptions([]);

		//opcje pola formularz
		$this->addElementTextarea('fieldOptions')
			->setLabel('opcje pola');

		//filtry
		$this->addElementTextarea('filterClasses')
			->setLabel('filtry');

		//walidatory
		$this->addElementTextarea('validatorClasses')
			->setLabel('walidatory');

		//waga w indeksie
		/* $this->addElementText('indexWeight')
		  ->setLabel('waga w indeksie')
		  ->setDescription('0-1000, im wyższa waga, tym wyższa pozycja w wyszukiwarce, 0 oznacza brak w wynikach')
		  ->setValue(0)
		  ->addValidatorNumberBetween(0, 1000); */
		
		//wymagany
		$this->addElementCheckbox('required')
			->setLabel('wymagany');

		//unikalny
		$this->addElementCheckbox('unique')
			->setLabel('unikalny');

		//zmaterializowany
		$this->addElementSelect('materialized')
			->setMultioptions([0 => 'nie', 1 => 'tak', 2 => 'tak, odziedziczony'])
			->setLabel('zmaterializowany')
			->setDescription('opcja administracyjna, zmiana może uszkodzić formularze zawierające ten atrybut');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz atrybut');
	}

}
