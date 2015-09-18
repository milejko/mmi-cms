<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Article;
use \Cms\Orm;

class Article {

	/**
	 * Multiopcje z artykułami
	 * @return array
	 */
	public static function getMultioptions() {
		return [null => '---'] + Orm\Article\Query::factory()
				->orderAscTitle()
				->findPairs('id', 'title');
	}

}