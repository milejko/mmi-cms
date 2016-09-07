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
		
		//klucz pola
		$this->addElementText('key')
			->setLabel('klucz')
			->addFilterUrl()
			->setRequired()
			->addValidatorAlnum('klucz może zawierać wyłącznie litery i cyfry')
			->addValidatorStringLength(2, 64)
			->addValidatorRecordUnique(new \Cms\Orm\CmsAttributeQuery, 'key', $this->getRecord()->id);

		//opis
		$this->addElementSelect('fieldClass')
			->setLabel('pole formularza')
			->setRequired()
			->setMultioptions($this->getRecord()->getFieldClasses());

		//filtry
		$this->addElementMultiCheckbox('filterArray')
			->setLabel('filtry')
			->setValue(explode(',', $this->getRecord()->filterClasses))
			->setMultioptions([
				'\Mmi\Filter\StripTags' => 'usunięcie HTML',
				'\Mmi\Filter\StringTrim' => 'usunięcie spacji z początku i końca',
				'\Mmi\Filter\Url' => 'filtr do url\'a',
		]);

		//walidatory
		$this->addElementMultiCheckbox('validatorArray')
			->setLabel('walidatory')
			->setValue(explode(',', $this->getRecord()->validatorClasses))
			->setMultioptions([
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
		$this->addElementSelect('materialized')
			->setMultioptions([0 => 'nie', 1 => 'tak', 2 => 'tak, odziedziczony'])
			->setLabel('zmaterializowany')
			->setDescription('opcja administracyjna, zmiana może uszkodzić formularze zawierające ten atrybut');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}
	
	/**
	 * Przed zapisem - spłaszczenie walidatorów i filtrów
	 * @return boolean
	 */
	public function beforeSave() {
		$this->getRecord()->validatorClasses = implode(',', $this->getElement('validatorArray')->getValue());
		$this->getRecord()->filterClasses = implode(',', $this->getElement('filterArray')->getValue());
		return true;
	}

}
