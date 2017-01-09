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
	 * Aktualizacja rekordu
	 * @return boolean 
	 */
	protected function _update() {
		//usunięcie cache
		\App\Registry::$cache->remove('widget-attributes-' . $this->id);
		return parent::_update();
	}

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
		//próba pobrania atrybutów z cache
		if (null === $attributeValues = \App\Registry::$cache->load($cacheKey = 'widget-attributes-' . $this->id)) {
			//pobieranie atrybutów
			\App\Registry::$cache->save($attributeValues = (new \Cms\Model\AttributeValueRelationModel('categoryWidgetRelation', $this->id))->getGrouppedAttributeValues(), $cacheKey);
		}
		//zwrot atrybutów
		return $attributeValues;
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
			\Mmi\App\FrontController::getInstance()->getLogger()->addWarning('Unable to decode widget configJson #' . $this->id);
		}
		//tworznie pustego configa
		if (!isset($configArr)) {
			$configArr = [];
		}
		$config = (new \Mmi\DataObject())->setParams($configArr);
		return $config;
	}

	/**
	 * Aktywacja 1/roboczy 2/deaktywacja 0
	 * @param int $state
	 */
	public function toggle($state = 0) {
		//aktywacja/roboczy/deaktywacja
		$this->active = (int) $state < 3 ? $state : 0;
		$this->save();
	}

}