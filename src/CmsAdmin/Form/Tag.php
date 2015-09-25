<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class Tag extends \Mmi\Form\Form {

	public function init() {

		$this->addElementText('tag')
			->setLabel('tag')
			->setRequired()
			->addFilter('StringTrim')
			->addValidatorStringLength(2, 64);

		$this->addElementSubmit('submit')
			->setLabel('zapisz')
			->setIgnore();
	}

}
