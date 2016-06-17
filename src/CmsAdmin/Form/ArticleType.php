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
class ArticleType extends \Cms\Form\Form {

	public function init() {

		//tytuł
		$this->addElementText('name')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorNotEmpty()
			->setLabel('nazwa');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz stronę');
	}

}
