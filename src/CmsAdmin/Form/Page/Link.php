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
 * Formularz linków w nawigatorze
 */
class Link extends PageAbstract {

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

		parent::init();
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
