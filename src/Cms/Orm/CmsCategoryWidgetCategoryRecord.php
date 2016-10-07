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
	public $active = 1;

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
			$config = \json_decode($this->configJson);
		} catch (\Exception $e) {
			
		}
		//tworznie pustego configa
		if (!isset($config)) {
			$config = new \stdClass();
		}
		//domyślnie pusty recordId
		$config->recordId = isset($config->recordId) ? $config->recordId : null;
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
