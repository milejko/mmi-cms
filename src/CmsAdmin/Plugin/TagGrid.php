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
 * Grid tagów
 */
class TagGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(\Cms\Orm\CmsTagQuery::factory());

		//nazwa taga
		$this->addColumnText('tag')
			->setLabel('tag');

		//operacje
		$this->addColumnOperation();
	}

}
