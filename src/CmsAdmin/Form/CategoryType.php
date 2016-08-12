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
	 * @return boolean
	 */
	public function afterSave() {
		//model relacji
		$relationModel = new \Cms\Model\AttributeRelationModel('cms_category_type', $this->getRecord()->id);
		//nowe id atrybutów
		$newAttributeIds = $this->getElement('attributeIds')->getValue();
		//bieżące id atrybutów
		$currentAttributeIds = $relationModel->getAttributeIds();
		//atrybuty do dodania
		foreach (array_diff($newAttributeIds, $currentAttributeIds) as $attributeId) {
			//dodawanie relacji
			$relationModel->createAttributeRelation($attributeId);
		}
		//atrybuty do usunięcia
		foreach (array_diff($currentAttributeIds, $newAttributeIds) as $attributeId) {
			//usuwanie wartości
			$this->_deleteValueRelationsByAttributeId($attributeId);
			//usuwanie relacji
			$relationModel->deleteAttributeRelation($attributeId);
		}
		return parent::afterSave();
	}

	/**
	 * Usuwanie relacji ze wszystkich kategorii dla danego atrybutu
	 * @param integer $attributeId
	 */
	protected function _deleteValueRelationsByAttributeId($attributeId	) {
		foreach ((new \Cms\Orm\CmsCategoryQuery)->whereCmsCategoryTypeId()
			->equals($this->getRecord()->id)
			->findPairs('id', 'id') as $categoryId) {
			(new \Cms\Model\AttributeValueRelationModel('category', $categoryId))
				->deleteAttributeValueRelationsByAttributeId($attributeId);
		}
	}

}
