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
		$this->addElementMultiCheckbox('attributeGroupIds')
			->setLabel('grupa atrybutów')
			->setMultioptions((new \Cms\Orm\CmsAttributeGroupQuery)->orderAscName()->findPairs('id', 'name'))
			->setValue(array_keys((new \Cms\Model\AttributeGroupRelationModel('cms_category_type', $this->getRecord()->id))
					->getAttributeGroupRelations()));

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
		return true;
	}

	public function afterSave() {
		//tworzenie relacji
		(new \Cms\Model\AttributeGroupRelationModel('cms_category_type', $this->getRecord()->id))
			->createAttributeGroupRelations($this->getElement('attributeGroupIds')->getValue());
		return true;
	}

}
