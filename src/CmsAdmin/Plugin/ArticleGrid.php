<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid artykułów
 */
class ArticleGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//domyślne zapytanie
		$this->setQuery(\Cms\Orm\CmsArticleQuery::factory());
		
		//indeks
		$this->addElementIndex();

		//tytuł
		$this->addElementText('title')
			->setLabel('tytuł');

		//treść
		$this->addElementText('text')
			->setLabel('treść');

		//data dodania
		$this->addElementText('dateAdd')
			->setLabel('data dodania');

		//data modyfikacji
		$this->addElementText('dateModify')
			->setLabel('data modyfikacji');

		//operacje
		$this->addElementOperation();
	}

}
