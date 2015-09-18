<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Route;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $pattern;
	public $replace;
	public $default;
	public $order;
	public $active;

	public function save() {
		\App\Registry::$cache->remove('\Mmi\Route');
		return parent::save();
	}

	public function toRouteArray() {
		$replace = [];
		$default = [];
		parse_str($this->replace, $replace);
		parse_str($this->default, $default);
		$route = [
			'pattern' => $this->pattern,
			'replace' => $replace,
			'default' => $default
		];
		return $route;
	}

}
