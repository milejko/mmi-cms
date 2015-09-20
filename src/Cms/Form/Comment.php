<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

class Comment extends \Mmi\Form\Component {

	public function init() {
		$this->_record->object = $this->getOption('object');
		$this->_record->objectId = $this->getOption('objectId');

		$this->addElementText('title')
			->setLabel('tytuł');

		$this->addElementTextarea('text')
			->setRequired()
			->setLabel('komentarz')
			->addValidatorNotEmpty();


		if (!\App\Registry::$auth->hasIdentity()) {
			$this->addElementText('signature')
				->setLabel('podpis');
		}

		$this->addElementSubmit('submit')
			->setLabel('dodaj komentarz');
	}

}
