<?php

namespace Cms\Orm;

/**
 * Rekord widgetu kategorii
 */
class CmsCategoryWidgetRecord extends \Mmi\Orm\Record {

	public $id;
	public $name;
	public $formClass;
	public $mvcParams;
	public $mvcPreviewParams;
	public $cacheLifetime = self::DEFAULT_CACHE_LIFETIME;

	//domyślna długość bufora
	const DEFAULT_CACHE_LIFETIME = 2592000;
	
	//interwały buforów
	const CACHE_LIFETIMES = [
		2592000 => 'po zmianie',
		0 => 'zawsze',
		60 => 'co minutę',
		300 => 'co 5 minut',
		600 => 'co 10 minut',
		3600 => 'co godzinę',
		28800 => 'co 8 godzin',
		86400 => 'raz na dobę',
	];

	/**
	 * Pobiera parametrów mvc jako request
	 * @return \Mmi\Http\Request
	 */
	public function getMvcParamsAsRequest() {
		$mvcParams = [];
		//parsowanie ciągu
		parse_str($this->mvcParams, $mvcParams);
		return new \Mmi\Http\Request($mvcParams);
	}

	/**
	 * Pobiera parametry podglądu mvc jako request
	 * @return \Mmi\Http\Request
	 */
	public function getMvcPreviewParamsAsRequest() {
		$mvcParams = [];
		//parsowanie ciągu
		parse_str($this->mvcPreviewParams, $mvcParams);
		return new \Mmi\Http\Request($mvcParams);
	}

	/**
	 * Aktualizacja widgeta
	 * @return boolean
	 */
	protected function _update() {
		//zapis z usunięciem bufora
		return parent::_update() && $this->clearCache();
	}

	/**
	 * Usuwanie zbuforowanych renderów widgetów
	 * @return boolean
	 */
	public function clearCache() {
		//iteracja po relacjach widgetów
		foreach ((new CmsCategoryWidgetCategoryQuery)
			->join('cms_category')->on('cms_category_id')
			->whereCmsCategoryWidgetId()
			->equals($this->id)
			->findPairs('cms_category_widget_category.id', 'cms_category.id') as $id => $categoryId) {
			//usuwanie bufora
			\App\Registry::$cache->remove('category-widget-html-' . $id);
			\App\Registry::$cache->remove('category-widget-model-' . $categoryId);
			\App\Registry::$cache->remove('category-html-' . $categoryId);
		}
		return true;
	}

}
