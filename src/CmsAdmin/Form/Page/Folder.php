<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Page;

/**
 * Formularz folderów w nawigatorze
 * @method \Cms\Orm\CmsNavigationRecord getRecord()
 */
class Folder extends \Cms\Form\Form {

	public function init() {
		//menu label
		$this->addElementText('label')
			->setLabel('Nazwa folderu')
			->setDescription('Nazwa będzie jednocześnie składową tytułu strony')
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

		//pozycja w drzewie
		$this->addElementSelect('parentId')
			->setLabel('Element nadrzędny')
			->setValue(\Mmi\App\FrontController::getInstance()->getRequest()->parent)
			->setMultioptions(\Cms\Model\Navigation::getMultioptions());

		//optional url
		$this->addElementSelect('visible')
			->setLabel('Widoczność')
			->setMultioptions([
				1 => 'widoczny',
				0 => 'ukryty',
			])
			->setDescription('Jeśli niewidoczny, jego dane nie wejdą do ścieżki tytułu i okruchów');

		$this->addElementDateTimePicker('dateStart')
			->setLabel('Data i czas włączenia')
			->addFilterEmptyToNull();

		$this->addElementDateTimePicker('dateEnd')
			->setLabel('Data i czas wyłączenia')
			->addFilterEmptyToNull();

		$this->addElementCheckbox('active')
			->setChecked()
			->setLabel('Włączony');

		//submit
		$this->addElementSubmit('submit')
			->setLabel('Zapisz')
			->setIgnore();
	}

	/**
	 * Przed zapisem reset adresu w mvc
	 * @return boolean
	 */
	public function beforeSave() {
		$this->getRecord()->module = null;
		$this->getRecord()->controller = null;
		$this->getRecord()->action = null;
		$this->getRecord()->uri = null;
		return true;
	}

}
