<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class Tag extends \Mmi\Form {

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
