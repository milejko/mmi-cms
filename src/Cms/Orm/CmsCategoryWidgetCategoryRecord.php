<?php

namespace Cms\Orm;

/**
 * Rekord łączenia widget - kategoria
 */
class CmsCategoryWidgetCategoryRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsCategoryWidgetId;
	public $cmsCategoryId;
	public $recordId;
	public $configJson;

	/**
	 * Kolejność
	 * @var integer
	 */
	public $order;

	/**
	 * Zwraca rekord kategorii
	 * @return CmsCategoryRecord
	 */
	public function getCategoryRecord() {
		//zwrot dołączonegj kategorii
		if ($this->getJoined('cms_category')) {
			return $this->getJoined('cms_category_widget');
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
	 * Zwraca konfigurację
	 * @return stdClass
	 */
	public function getConfig() {
		//próba dekodowania konfiguracji json
		try {
			return \json_decode($this->configJson);
		} catch (\Exception $e) {
			return new \stdClass;
		}
	}

}
