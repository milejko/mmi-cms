<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

class Article {

	/**
	 * Multiopcje z artykułami
	 * @return array
	 */
	public static function getMultioptions() {
		return [null => '---'] + (new \Cms\Orm\CmsArticleQuery)
				->orderAscTitle()
				->findPairs('id', 'title');
	}

}