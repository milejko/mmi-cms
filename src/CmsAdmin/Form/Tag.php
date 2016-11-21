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
 * Formularz tagów
 */
class Tag extends \Mmi\Form\Form {

	public function init() {

		//tag
		$this->addElementText('tag')
			->setLabel('tag')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 64)
			->addValidatorRecordUnique((new \Cms\Orm\CmsTagQuery), 'tag', $this->getRecord()->id);
		
		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz tag');
	}

}
