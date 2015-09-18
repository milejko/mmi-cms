<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
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
		return Orm\Contact\Option\Query::factory()
				->orderAscOrder()
				->orderAscName()
				->findPairs('id', 'name');
	}

}
