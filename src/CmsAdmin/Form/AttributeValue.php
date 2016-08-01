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
 * Formularz wartości atrybutu
 */
class AttributeValue extends \Mmi\Form\Form {

	public function init() {

		//nazwa
		$this->addElementText('value')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(1, 255);

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('dodaj');
	}

}
