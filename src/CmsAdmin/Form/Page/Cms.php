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
class Cms extends \Cms\Form\Component {

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

		//system object
		$this->addElementSelect('object')
			->setLabel('Obiekt CMS')
			->setDescription('Istniejące obiekty CMS')
			->setRequired()
			->setOption('id', 'objectId');

		$object = $this->getElement('object');
		$object->addMultiOption(null, null);
		foreach (\CmsAdmin\Model\Reflection::getActions() as $action) {
			$object->addMultiOption($action['path'], $action['module'] . ': ' . $action['controller'] . ' - ' . $action['action']);
		}
		$object->setValue($this->_calculateObject());
		
		//optional params
		$this->addElementText('params')
			->setLabel('Parametry obiektu')
			->setDescription('Dodatkowe parametry adresu w obiekcie');

		$this->addElementCheckbox('absolute')
			->setLabel('Link bezwzględny');

		$this->addElementSelect('https')
			->setLabel('Połączenie HTTPS')
			->setMultiOptions([
				null => 'bez zmian',
				'0' => 'wymuś http',
				'1' => 'wymuś https',
		]);

		//optional url
		$this->addElementSelect('visible')
			->setLabel('Pokazuj w menu')
			->setMultiOptions([
				1 => 'widoczny',
				0 => 'ukryty',
		]);

		$this->addElementCheckbox('nofollow')
			->setLabel('Atrybut rel="nofollow"');

		$this->addElementCheckbox('blank')
			->setLabel('W nowym oknie');

		//pozycja w drzewie
		$this->addElementSelect('parentId')
			->setLabel('Element nadrzędny')
			->setValue(\Mmi\App\FrontController::getInstance()->getRequest()->parent)
			->setMultiOptions(\Cms\Model\Navigation::getMultiOptions());

		$this->addElementDateTimePicker('dateStart')
			->setLabel('Data i czas włączenia');

		$this->addElementDateTimePicker('dateEnd')
			->setLabel('Data i czas wyłączenia');

		$this->addElementCheckbox('active')
			->setValue(true)
			->setLabel('Włączony');

		//submit
		$this->addElementSubmit('submit')
			->setLabel('Zapisz')
			->setIgnore();
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
