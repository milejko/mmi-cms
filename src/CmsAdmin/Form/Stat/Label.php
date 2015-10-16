<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Stat;

class Label extends \Mmi\Form\Form {

	public function init() {

		$this->addElementSelect('object')
			->setLabel('klucz')
			->setRequired()
			->setMultioptions(\Cms\Model\Stat::getUniqueObjects());

		$this->addElementText('label')
			->setLabel('nazwa statystyki')
			->setRequired();

		$this->addElementTextarea('description')
			->setLabel('opis');

		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

}
