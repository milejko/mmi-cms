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
 * Formularz typu kategorii
 */
class CategoryType extends \Cms\Form\Form {

	public function init() {

		//nazwa
		$this->addElementText('name')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorNotEmpty()
			->addValidatorRecordUnique(new \Cms\Orm\CmsCategoryTypeQuery, 'name', $this->getRecord()->id)
			->setLabel('nazwa');
		
		//klasa modułu wyświetlania
		$this->addElementSelect('mvcParams')
			->setMultioptions([null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard(3))
			->setRequired()
			->addValidatorNotEmpty()
			->setLabel('moduł wyświetlania');
		
		//ustawienie bufora
		$this->addElementSelect('cacheLifetime')
			->setLabel('odświeżanie')
			->setMultioptions(\Cms\Orm\CmsCategoryRecord::CACHE_LIFETIMES)
			->setValue(\Cms\Orm\CmsCategoryRecord::DEFAULT_CACHE_LIFETIME)
			->addFilterEmptyToNull();

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz szablon');
	}

	/**
	 * Przed zapisem
	 * @return boolean
	 */
	public function beforeSave() {
		//kalkulacja klucza
		$this->getRecord()->key = (new \Mmi\Filter\Url)->filter($this->getRecord()->name);
		return parent::beforeSave();
	}

}
