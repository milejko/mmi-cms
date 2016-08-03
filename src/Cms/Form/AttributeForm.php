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

	/**
	 * Wstawienie atrybutów
	 * @param type $object
	 * @param type $objectId
	 */
	public function insertAttributes($object, $groupObject, $groupObjectId) {
		
		$groupRelations = new \Cms\Model\AttributeGroupRelationModel($groupObject, $groupObjectId);
		foreach ($groupRelations->getAttributeGroupRelations() as $groupRelation) {
			$groupRelation->cmsAttributeG;
		}
		
		$this->_object = $object;
		
		$this->addElementLabel('#first')
			->setLabel('first');
	}

	/**
	 * Wywołuje walidację i zapis rekordu powiązanego z formularzem.
	 * @return bool
	 */
	public function save() {
		parent::save();
		//brak obiektu
		if (!$this->_object) {
			return $this->isSaved();
		}
		//zapis relacji
		$valueRelation = new \Cms\Model\AttributeValueRelationModel($this->_object, $this->getRecord()->id);
		$attributeValues = [];
		$valueRelation->createAttributeValueRelations($attributeValues);
		return $this->isSaved();
	}

}
