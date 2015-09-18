<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Widget;

class Text extends \Cms\Form {

	public function init() {

		$this->addElementTextarea('data')
			->setLabel('Tekst');

		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

}
