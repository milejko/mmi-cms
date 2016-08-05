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

		//szablon (moduł/kontroler/akcja)
		$this->addElementText('template')
			->setLabel('szablon')
			->addValidatorRegex('/^[a-zA-Z0-9]+\/[a-zA-Z0-9]+\/[a-zA-Z0-9]+$/', 'Szablon w formacie - moduł/kontroler/akcja')
			->setRequired();

		//grupy atrybutów
		$this->addElementMultiCheckbox('attributeIds')
			->setLabel('atrybuty')
			->setMultioptions((new \Cms\Orm\CmsAttributeQuery)->orderAscName()->findPairs('id', 'name'))
			->setValue((new \Cms\Model\AttributeRelationModel('cms_category_type', $this->getRecord()->id))->getAttributeIds());

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz stronę');
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

	/**
	 * Po zapisie
	 * @TODO po zmianie relacji - usunięcie zbędnych wiązań wartości
	 * @return boolean
	 */
	public function afterSave() {
		//tworzenie relacji z atrybutami
		(new \Cms\Model\AttributeRelationModel('cms_category_type', $this->getRecord()->id))
			->createAttributeRelations($this->getElement('attributeIds')->getValue());
		return parent::afterSave();
	}

}
