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
 * Abstrakcyjna klasa formularza
 */
abstract class PageAbstract extends \Cms\Form\Form {

	public function init() {
		//link bezwzględny
		$this->addElementCheckbox('absolute')
			->setLabel('Link bezwzględny');

		//https
		$this->addElementSelect('https')
			->setLabel('Połączenie HTTPS')
			->setMultioptions([
				null => 'bez zmian',
				'0' => 'wymuś http',
				'1' => 'wymuś https']);

		//optional url
		$this->addElementSelect('visible')
			->setLabel('Pokazuj w menu')
			->setMultioptions([
				1 => 'widoczny',
				0 => 'ukryty',
		]);

		//nofollow
		$this->addElementCheckbox('nofollow')
			->setLabel('Atrybut rel="nofollow"');

		//nowe okno
		$this->addElementCheckbox('blank')
			->setLabel('W nowym oknie');

		//pozycja w drzewie
		$this->addElementSelect('parentId')
			->setLabel('Element nadrzędny')
			->setValue(\Mmi\App\FrontController::getInstance()->getRequest()->parent)
			->setMultioptions(\Cms\Model\Navigation::getMultioptions());

		//data rozpoczęcia publikacji
		$this->addElementDateTimePicker('dateStart')
			->setLabel('Data i czas włączenia');

		//data zakończenia
		$this->addElementDateTimePicker('dateEnd')
			->setLabel('Data i czas wyłączenia');

		//włączony
		$this->addElementCheckbox('active')
			->setChecked()
			->setLabel('Włączony');

		//submit
		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

}
