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
 * Formularz edycji szegółów kategorii
 */
class Category extends \Cms\Form\Form {

	public function init() {

		//nazwa kategorii
		$this->addElementText('name')
			->setLabel('nazwa kategorii')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 64);
		
		//aktywna
		$this->addElementCheckbox('active')
			->setChecked()
			->setLabel('włączona');
		
		//opis
		$this->addElementTinyMce('description')
			->setHeight(200)
			->setLabel('opis');
		
		//id
		$this->addElementHidden('id');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz');
		
	}

}
