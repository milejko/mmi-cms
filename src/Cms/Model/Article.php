<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
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