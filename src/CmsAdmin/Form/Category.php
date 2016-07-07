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
			->setLabel('nazwa')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//lead
		$this->addElementTextarea('lead')
			->setLabel('podsumowanie');

		//treść
		$this->addElementTinyMce('text')
			->setLabel('treść');

		//aktywna
		$this->addElementCheckbox('active')
			->setChecked()
			->setLabel('włączona');

		//widoczność
		$this->addElementCheckbox('visible')
			->setChecked()
			->setLabel('widoczna');

		//zapis
		$this->addElementSubmit('submit1')
			->setLabel('zapisz');

		$this->addElementLabel('label1')
			->setLabel('Zaawansowane i SEO');

		//nazwa kategorii
		$this->addElementText('title')
			->setLabel('meta tytuł')
			->setDescription('jeśli brak, użyta zostanie nazwa')
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//meta description
		$this->addElementTextarea('description')
			->setLabel('meta opis')
			->setDescription('jeśli brak, użyte zostanie podsumowanie');

		//https
		$this->addElementSelect('https')
			->setMultioptions([null => 'bez zmian', '0' => 'wymuś brak https', 1 => 'wymuś https'])
			->setLabel('https');

		//blank
		$this->addElementCheckbox('blank')
			->setLabel('w nowym oknie');

		//blank
		$this->addElementCheckbox('follow')
			->setChecked()
			->setLabel('widoczny dla wyszukiwarek');

		//zapis
		$this->addElementSubmit('submit2')
			->setLabel('zapisz');
	}

}
