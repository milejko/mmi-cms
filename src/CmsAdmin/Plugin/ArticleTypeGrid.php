<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid artykułów
 */
class ArticleTypeGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//domyślne zapytanie
		$this->setQuery(new \Cms\Orm\CmsArticleTypeQuery);
		
		//nazwa typu
		$this->addColumnText('name')
			->setLabel('nazwa');

		//operacje
		$this->addColumnOperation();
	}

}
