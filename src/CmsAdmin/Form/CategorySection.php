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
 * Formularz edycji sekcji kategorii
 */
class CategorySection extends \Cms\Form\AttributeForm {

	public function init() {

		//nazwa kategorii
		$this->addElementSelect('widgets')
			->setMultioptions([])
			->setLabel('dostępne widgety');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('dodaj widget');
	}

}
