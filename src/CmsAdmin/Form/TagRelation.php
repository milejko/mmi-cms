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
 * Klasa formularza relacji tagów
 */
class TagRelation extends \Mmi\Form\Form {

	public function init() {
		
		//tag
		$this->addElementText('tag')
			->setLabel('tag')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 64);
		
		//ustawienie wartości tagu
		if ($this->getRecord()->cmsTagId && (null !== $tagRecord = (new \Cms\Orm\CmsTagQuery)->findPk($this->getRecord()->cmsTagId))) {
			$this->getElement('tag')->setValue($tagRecord->tag);
		}

		//obiekt
		$this->addElementText('object')
			->setLabel('zasób')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 64);

		//id obiektu
		$this->addElementText('objectId')
			->setLabel('ID zasobu')
			->addFilterEmptyToNull()
			->addValidatorInteger()
			->addValidatorNumberBetween(0, 100000000);

		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

	/**
	 * Przed zapisem odnalezienie identyfikatora wprowadzonego tagu
	 * @return boolean
	 */
	public function beforeSave() {
		$tag = $this->getElement('tag')->getValue();
		//wyszukanie tagu
		if (null === $tagRecord = (new \Cms\Orm\CmsTagQuery)
			->whereTag()->equals($tag)
			->findFirst()) {
			//utworzenie tagu
			$tagRecord = new \Cms\Orm\CmsTagRecord;
			$tagRecord->tag = $tag;
			$tagRecord->save();
		}
		//przypisanie id tagu
		$this->getRecord()->cmsTagId = $tagRecord->id;
		return true;
	}

}
