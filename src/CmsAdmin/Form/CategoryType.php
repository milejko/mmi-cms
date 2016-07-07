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
 * Formularz typu artykułu
 */
class CategoryType extends \Cms\Form\Form {

	public function init() {

		//tytuł
		$this->addElementText('name')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorNotEmpty()
			->addValidatorRecordUnique(new \Cms\Orm\CmsCategoryTypeQuery, 'name', $this->getRecord()->id)
			->setLabel('nazwa');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz stronę');
	}
	
	public function beforeSave() {
		$this->getRecord()->key = (new \Mmi\Filter\Url)->filter($this->getRecord()->name);
		return true;
	}

}
