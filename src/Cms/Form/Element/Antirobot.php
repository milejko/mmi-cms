<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

class Antirobot extends \Mmi\Form\Element\Hidden {

	/**
	 * Ignorowanie tego pola, pole obowiązkowe, automatyczna walidacja
	 */
	public function init() {
		$this->setIgnore()
			->setRequired()
			->addValidator('Antirobot', ['name' => $this->getOption('name')]);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$this->setValue(\Mmi\Validate\Antirobot::generateCrc());
		return parent::fetchField();
	}

}
