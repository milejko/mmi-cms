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

		//lista widgetów
		$widgets = [null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard(3, '/widget/');

		$this->addElementText('name')
			->setLabel('nazwa')
			->setRequired()
			->addValidatorStringLength(3, 64);

		$this->addElementSelect('mvcParams')
			->setLabel('adres modułu wyświetlania')
			->setMultioptions($widgets)
			->setRequired()
			->addValidatorNotEmpty();

		$this->addElementSelect('mvcPreviewParams')
			->setLabel('adres modułu podglądu')
			->setMultioptions($widgets)
			->setRequired()
			->addValidatorNotEmpty();

		$this->addElementText('formClass')
			->setLabel('klasa formularza')
			->setDescription('dane i konfiguracja')
			->addFilterEmptyToNull()
			->addValidatorStringLength(3, 64);

		//atrybuty
		$this->addElementMultiCheckbox('attributeIds')
			->setLabel('atrybuty')
			->setMultioptions((new \Cms\Orm\CmsAttributeQuery)->orderAscName()->findPairs('id', 'name'))
			->setValue((new \Cms\Model\AttributeRelationModel('cmsCategoryWidget', $this->getRecord()->id))->getAttributeIds());

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz widget');
	}

	/**
	 * Po zapisie
	 * @return boolean
	 */
	public function afterSave() {
		//model relacji
		$relationModel = new \Cms\Model\AttributeRelationModel('cmsCategoryWidget', $this->getRecord()->id);
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
	protected function _deleteValueRelationsByAttributeId($attributeId) {
		foreach ((new \Cms\Orm\CmsCategoryWidgetCategoryQuery)->whereCmsCategoryWidgetId()
			->equals($this->getRecord()->id)
			->findPairs('id', 'id') as $categoryWidgetCategoryId) {
			(new \Cms\Model\AttributeValueRelationModel('categoryWidgetRelation', $categoryWidgetCategoryId))
				->deleteAttributeValueRelationsByAttributeId($attributeId);
		}
	}

}
