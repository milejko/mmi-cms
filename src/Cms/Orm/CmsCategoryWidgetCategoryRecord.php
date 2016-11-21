<?php

namespace Cms\Orm;

/**
 * Rekord łączenia widget - kategoria
 */
class CmsCategoryWidgetCategoryRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsCategoryWidgetId;
	public $cmsCategoryId;
	public $configJson;
	public $active = 1;

	/**
	 * Kolejność
	 * @var integer
	 */
	public $order;

	/**
	 * Wartości atrybutów
	 * @var \Mmi\DataObject
	 */
	private $_attributeValues;

	/**
	 * Zwraca rekord kategorii
	 * @return CmsCategoryRecord
	 */
	public function getCategoryRecord() {
		//zwrot dołączonegj kategorii
		if ($this->getJoined('cms_category')) {
			return $this->getJoined('cms_category');
		}
		//zwrot znalezionegj kategorii
		return (new CmsCategoryQuery)->findPk($this->cmsCategoryId);
	}

	/**
	 * Zwraca rekord widgeta
	 * @return CmsCategoryWidgetRecord
	 */
	public function getWidgetRecord() {
		//zwrot dołączonego widgeta
		if ($this->getJoined('cms_category_widget')) {
			return $this->getJoined('cms_category_widget');
		}
		//zwrot znalezionego widgeta
		return (new CmsCategoryWidgetQuery)->findPk($this->cmsCategoryWidgetId);
	}

	/**
	 * Pobiera rekordy wartości atrybutów w formie obiektu danych
	 * @see \Mmi\DataObiect
	 * @return \Mmi\DataObject
	 */
	public function getAttributeValues() {
		//atrybuty już pobrane
		if (null !== $this->_attributeValues) {
			return $this->_attributeValues;
		}
		//pobieranie atrybutów
		return $this->_attributeValues = (new \Cms\Model\AttributeValueRelationModel('categoryWidgetRelation', $this->id))->getGrouppedAttributeValues();
	}

	/**
	 * Zwraca konfigurację
	 * @return \Mmi\DataObject
	 */
	public function getConfig() {
		//próba dekodowania konfiguracji json
		try {
			$configArr = \json_decode($this->configJson, true);
		} catch (\Exception $e) {
			
		}
		//tworznie pustego configa
		if (!isset($configArr)) {
			$configArr = [];
		}
		$config = (new \Mmi\DataObject())->setParams($configArr);
		return $config;
	}

	/**
	 * Aktywacja/deaktywacja
	 */
	public function toggle() {
		//aktywacja/deaktywacja
		$this->active = $this->active ? false : true;
		$this->save();
	}

}
