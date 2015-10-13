<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz edycji aktualności
 */
class News extends \Cms\Form\Form {

	public function init() {

		//tytuł
		$this->addElementText('title')
			->setLabel('Tytuł artykułu')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorNotEmpty();

		//wewnętrzny
		$this->addElementCheckbox('internal')
			->setLabel('Artykuł wewnętrzny')
			->setChecked();

		//url
		$this->addElementText('uri')
			->setLabel('Link do treści zewnętrznej');

		//zajawka
		$this->addElementTinyMce('lead')
			->setLabel('Podsumowanie (zajawka)');

		//text
		$this->addElementTinyMce('text')
			->setLabel('Treść')
			->setOption('img', 'news:' . $this->_record->id)
			->setModeAdvanced();

		//publikacjia
		$this->addElementCheckbox('visible')
			->setLabel('Publikacja');

		//dołączenie plików
		$this->addElementUploader('uploader')
			->setLabel('Dołącz pliki');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

}
