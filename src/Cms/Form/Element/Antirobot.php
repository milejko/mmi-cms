<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element utrudniający wysłanie formularza robotom
 */
class Antirobot extends \Mmi\Form\Element\Hidden {

	/**
	 * Ignorowanie tego pola, pole obowiązkowe, automatyczna walidacja
	 */
	public function init() {
		$this->setIgnore()
			->setRequired()
			->addValidator(new \Cms\Validator\Antirobot());
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$this->setValue(\Cms\Validator\Antirobot::generateCrc());
		return parent::fetchField();
	}

}
