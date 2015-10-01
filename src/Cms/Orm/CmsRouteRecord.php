<?php

namespace Cms\Orm;

/**
 * Rekord tras CMS
 */
class CmsRouteRecord extends \Mmi\Orm\Record {

	public $id;
	public $pattern;
	public $replace;
	public $default;
	public $order;
	public $active;

	/**
	 * Zapis trasy
	 * @return type
	 */
	public function save() {
		\App\Registry::$cache->remove('Mmi-Route');
		return parent::save();
	}

	/**
	 * Zrzut tras do tablicy
	 * @return array
	 */
	public function toRouteArray() {
		$replace = [];
		$default = [];
		parse_str($this->replace, $replace);
		parse_str($this->default, $default);
		return [
			'pattern' => $this->pattern,
			'replace' => $replace,
			'default' => $default
		];
	}

}
