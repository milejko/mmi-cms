<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;
use \Cms\Orm;

class Page {

	/**
	 * 
	 * @param integer $id
	 * @return \Cms\Orm\Page\Record
	 */
	public static function firstById($id) {
		$cacheKey = 'Cms-Page-' . $id;
		if (null !== ($record = \App\Registry::$cache->load($cacheKey))) {
			return $record;
		}
		$record = Orm\Page\Query::activeById($id)
			->findFirst();
		\App\Registry::$cache->save($record, $cacheKey, 14400);
		return $record;
	}

}
