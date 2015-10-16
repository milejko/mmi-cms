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
 * Grid aktualności
 */
class NewsGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery((new \Cms\Orm\CmsNewsQuery));

		//data dodania
		$this->addColumnText('dateAdd')
			->setLabel('data dodania');

		//data modyfikacji
		$this->addColumnText('dateModify')
			->setLabel('data modyfikacji');

		//tytuł
		$this->addColumnText('title')
			->setLabel('tytuł');

		//treść
		$this->addColumnText('text')
			->setLabel('treść aktualności');

		//operacje
		$this->addColumnOperation();
	}

}
