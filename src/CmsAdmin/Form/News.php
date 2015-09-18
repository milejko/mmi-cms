<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class News extends \Cms\Form {

	public function init() {

		//ustawia zabezpieczenie CSRF
		$this->setSecured();

		$this->addElementText('title')
			->setLabel('Tytuł artykułu')
			->setRequired()
			->addFilter('stringTrim')
			->addValidatorNotEmpty();

		$this->addElementCheckbox('internal')
			->setLabel('Artykuł wewnętrzny')
			->setValue(1);

		$this->addElementText('uri')
			->setLabel('Link do treści zewnętrznej');

		$this->addElementTinyMce('lead')
			->setLabel('Podsumowanie (zajawka)');

		$this->addElementTinyMce('text')
			->setLabel('Treść')
			->setOption('img', 'news:' . $this->_record->id)
			->setModeAdvanced();

		$this->addElementSelect('visible')
			->setLabel('Publikacja')
			->setMultiOptions([
				1 => 'włączony',
				0 => 'wyłączony',
			]);

		$this->addElementUploader('uploader')
			->setLabel('Dołącz pliki');

		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

}
