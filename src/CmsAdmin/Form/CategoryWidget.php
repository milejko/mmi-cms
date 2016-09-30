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
 * Formularz edycji widgetu kategorii
 */
class CategoryWidget extends \Cms\Form\Form {

	public function init() {
		
		$this->addElementText('name')
			->setLabel('nazwa')
			->setRequired()
			->addValidatorStringLength(3, 64);

		$this->addElementText('mvcParams')
			->setLabel('adres modułu wyświetlania')
			->setRequired()
			->addValidatorRegex('@module\=[a-zA-Z0-9\&\=]+@', 'niepoprawny adres modułu cms');
		
		$this->addElementText('mvcPreviewParams')
			->setLabel('adres modułu podglądu')
			->setRequired()
			->addValidatorRegex('@module\=[a-zA-Z0-9\&\=]+@', 'niepoprawny adres modułu cms');
		
		$this->addElementText('recordClass')
			->setLabel('klasa rekordu danych')
			->addValidatorStringLength(3, 64);

		$this->addElementText('formClass')
			->setLabel('klasa formularza')
			->setDescription('dane i konfiguracja')
			->addValidatorStringLength(3, 64);
		
		//zapis
		$this->addElementSubmit('submit')
			->setLabel('dodaj widget');
	}

}
