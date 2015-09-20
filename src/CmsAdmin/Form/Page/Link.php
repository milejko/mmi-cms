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
 * Formularz linków w nawigatorze
 */
class Link extends \Cms\Form\Component {

	public function init() {
		//menu label
		$this->addElementText('label')
			->setLabel('Tekst linku (href-text)')
			->setRequired()
			->addValidatorStringLength(2, 64);

		//optional url
		$this->addElementText('uri')
			->setLabel('Adres strony')
			->setDescription('w formacie http://...')
			->setRequired()
			->addValidatorStringLength(6, 255);

		//menu label
		$this->addElementText('title')
			->setLabel('Tytuł linku');

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
			->setValue(1)
			->setLabel('Włączony');

		//submit
		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

	/**
	 * Przed zapisem reset adresu w mvc
	 * @return boolean
	 */
	public function beforeSave() {
		$this->getRecord()->module = null;
		$this->getRecord()->controller = null;
		$this->getRecord()->action = null;
		return true;
	}

}
