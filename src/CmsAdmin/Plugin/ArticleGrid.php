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
class ArticleGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//domyślne zapytanie
		$this->setQuery((new \Cms\Orm\CmsArticleQuery)->whereObject()->equals(null));
		
		//tytuł
		$this->addColumnText('title')
			->setLabel('tytuł');
		
		$this->addColumnSelect('cmsArticleTypeId')
			->setMultioptions([null => '---'] + (new \Cms\Orm\CmsArticleTypeQuery)->findPairs('id', 'name'))
			->setLabel('typ');

		//data dodania
		$this->addColumnText('dateAdd')
			->setLabel('data dodania');

		//data modyfikacji
		$this->addColumnText('dateModify')
			->setLabel('data modyfikacji');

		//aktywność
		$this->addColumnCheckbox('active')
			->setLabel('aktywny');
		
		//operacje
		$this->addColumnOperation();
	}

}
