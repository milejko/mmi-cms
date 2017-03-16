<?php

namespace Cms\Orm;

/**
 * Rekord typów kategorii (szablonów)
 */
class CmsCategoryTypeRecord extends \Mmi\Orm\Record {

	public $id;
	public $name;
	public $key;
	public $mvcParams;
	public $cacheLifetime;

}
