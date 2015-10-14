<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Page;

/**
 * Formularz obiektów cms w nawigatorze
 */
class Cms extends PageAbstract {

	public function init() {
		//menu label
		$this->addElementText('label')
			->setLabel('Nazwa w menu')
			->setRequired()
			->addValidatorStringLength(2, 64);

		//opcjonalny tytuł
		$this->addElementText('title')
			->setLabel('Tytuł strony (meta/title)')
			->setDescription('Jeśli nie wypełniony, zostanie użyta nazwa w menu')
			->addValidatorStringLength(3, 128);

		//opcjonalny opis
		$this->addElementTextarea('description')
			->setLabel('Opis strony (meta/description)')
			->addValidatorStringLength(3, 1024);

		//opcjonalne keywords
		$this->addElementText('keywords')
			->setLabel('Słowa kluczowe (meta/keywords)')
			->addValidatorStringLength(3, 512);

		$this->addElementCheckbox('independent')
			->setLabel('Niezależne meta');

		$options = [null => '---'];
		foreach (\CmsAdmin\Model\Reflection::getActions() as $action) {
			$options[$action['path']] = $action['module'] . ': ' . $action['controller'] . ' - ' . $action['action'];
		}
		
		//system object
		$this->addElementSelect('object')
			->setLabel('Obiekt CMS')
			->setDescription('Istniejące obiekty CMS')
			->setRequired()
			->setMultioptions($options)
			->setValue($this->_calculateObject())
			->setOption('id', 'objectId');
		
		//optional params
		$this->addElementText('params')
			->setLabel('Parametry obiektu')
			->setDescription('Dodatkowe parametry adresu w obiekcie');
		
		//nadrzędne pola
		parent::init();
	}

	/**
	 * Przed zapisem konwersja obiektu do model, kontroler, akcja
	 * @return boolean
	 */
	public function beforeSave() {
		$params = explode('_', $this->getElement('object')->getValue());
		if (count($params) != 3) {
			return false;
		}
		//ustawianie pól na rekordzie
		$this->getRecord()->module = $params[0];
		$this->getRecord()->controller = $params[1];
		$this->getRecord()->action = $params[2];
		$this->getRecord()->uri = null;
		return true;
	}

	/**
	 * Obliczanie wartości obiektu na podstawie rekordu
	 * @return string
	 */
	protected function _calculateObject() {
		$object = $this->getRecord()->module ? $this->getRecord()->module : 'mmi';
		$object .= '_' . ($this->getRecord()->controller ? $this->getRecord()->controller : 'index');
		return $object . '_' . ($this->getRecord()->action ? $this->getRecord()->action : 'index');
	}
	
}
