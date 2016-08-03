<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

/**
 * Formularz CMS z atrybutami
 */
abstract class AttributeForm extends Form {

	/**
	 * Obiekt
	 * @var string
	 */
	private $_object;
	
	private $_cmsAttributeElements = [];

	public function initAttributes($object, $objectId, $saveToObject) {
		$attributeRelations = new \Cms\Model\AttributeRelationModel($object, $objectId);
		$valueRelations = new \Cms\Model\AttributeValueRelationModel($saveToObject, $this->getRecord()->id);
		$this->addElementLabel('attributes')
			->setLabel('Atrybuty');
		foreach ($attributeRelations->getAttributes() as $attribute) {
			$fieldClass = $attribute->fieldClass;
			$this->_cmsAttributeElements[$attribute->id] = new $fieldClass('cmsAttribute-' . $attribute->id);
			//multiopcje
			if ($attribute->isRestricted()) {
				$this->_cmsAttributeElements[$attribute->id]->setMultioptions([null => '---'] + (new \Cms\Orm\CmsAttributeValueQuery)->whereCmsAttributeId()->equals($attribute->id)->findPairs('id', 'value'));
			}
			//konfiguracja pola
			$this->_cmsAttributeElements[$attribute->id]->setLabel($attribute->name)
				->setDescription($attribute->description)
				->setValue($attribute->isRestricted() ? $valueRelations->getAttributeValueIds() : $this->_firstValue($valueRelations, $attribute->id));
			$this->addElement($this->_cmsAttributeElements[$attribute->id]);
		}
		$this->_object = $saveToObject;
	}

	/**
	 * Wywołuje walidację i zapis rekordu powiązanego z formularzem.
	 * @return bool
	 */
	public function save() {
		//nie zapisano
		if (!parent::save()) {
			return;
		}
		//brak obiektu
		if (!$this->_object) {
			return $this->isSaved();
		}
		//zapis relacji
		$valueRelations = new \Cms\Model\AttributeValueRelationModel($this->_object, $this->getRecord()->id);
		$attributeValues = [];
		foreach ($this->_cmsAttributeElements as $attributeId => $element) {
			if (is_array($element->getValue())) {
				$attributeValues = array_merge($attributeValues, $element->getValue());
				continue;
			}
			if (null === $valueRecord = (new \Cms\Orm\CmsAttributeValueQuery)->whereValue()->equals($element->getValue())->findFirst()) {
				$valueRecord = new \Cms\Orm\CmsAttributeValueRecord();
				$valueRecord->value = $element->getValue();
				$valueRecord->cmsAttributeId = $attributeId;
				//brak możliwości zapisu
				if (!$valueRecord->save()) {
					continue;
				}
			}
			$attributeValues[] = $valueRecord->id;
		}
		$valueRelations->createAttributeValueRelations($attributeValues);
		return $this->isSaved();
	}

	/**
	 * Pobiera pierwszą wartość
	 * @param \Cms\Model\AttributeValueRelationModel $valueRelationModel
	 * @return mixed
	 */
	private function _firstValue(\Cms\Model\AttributeValueRelationModel $valueRelationModel, $attributeId) {
		foreach ($valueRelationModel->getAttributeValues() as $valueRecord) {
			if ($valueRecord->cmsAttributeId == $attributeId) {
				return $valueRecord->value;
			}			
		}
	}

}
