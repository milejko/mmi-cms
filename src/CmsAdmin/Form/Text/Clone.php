<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Text;

/**
 * Formularz kopiowania tekstów statycznych
 */
class Copy extends \Mmi\Form {

	public function init() {

		$langMultiOptions = [];
		//wybór z dostępnych języków
		foreach (\App\Registry::$config->languages as $lang) {
			if ($lang == \Mmi\Controller\Front::getInstance()->getRequest()->lang) {
				continue;
			}
			$langMultiOptions[$lang] = $lang;
		}

		//źródło
		$this->addElementSelect('source')
			->setLabel('Wybierz język źródłowy')
			->setDescription('Brakujące klucze w bieżącym języku zostaną utworzone, wartości zostaną uzupełnione wartościami z języka źródłowego')
			->setMultiOptions($langMultiOptions);

		$this->addElementSubmit('submit')
			->setLabel('klonuj teksty');
	}
	
	/**
	 * Zapis kluczy
	 * @return boolean
	 */
	public function beforeSave() {
		$lang = \Mmi\Controller\Front::getInstance()->getRequest()->lang;
		foreach (\Cms\Orm\Text\Query::byLang($this->source)->find() as $record) {
			/* @var $record \Cms\Orm\Text\Record */
			if (\Cms\Orm\Text\Query::byKeyLang($record->key, $lang)->findFirst() !== null) {
				continue;
			}
			//nowy rekord
			$r = \Cms\Orm\Text\Record();
			$r->lang = $lang;
			$r->key = $record->key;
			$r->content = $record->content;
			$r->save();
		}
		return true;
	}

}
