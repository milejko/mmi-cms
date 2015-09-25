<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Widget;

class Text extends \Cms\Form\Form {

	public function init() {

		$this->addElementTextarea('data')
			->setLabel('Tekst');

		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

}
