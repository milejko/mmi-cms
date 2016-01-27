<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;
use \Cms\Orm;

class Contact {

	/**
	 * Zwraca multiopcje tematow kontaktu
	 * @return array
	 */
	public static function getMultioptions() {
		return (new Orm\CmsContactOptionQuery)
				->orderAscName()
				->findPairs('id', 'name');
	}

}
