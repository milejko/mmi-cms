<?php

namespace Cms\Orm;

/**
 * Rekord widgetu kategorii
 */
class CmsCategoryWidgetRecord extends \Mmi\Orm\Record {

	public $id;
	public $name;
	public $recordClass;
	public $formClass;
	public $mvcParams;
	public $mvcPreviewParams;
	
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


}
