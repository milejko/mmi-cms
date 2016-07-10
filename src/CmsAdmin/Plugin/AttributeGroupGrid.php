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
 * Grid grup atrybutów
 */
class AttributeGroupGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(new \Cms\Orm\CmsAttributeGroupQuery);

		//nazwa taga
		$this->addColumnText('name')
			->setLabel('nazwa');

		//klasa pola
		$this->addColumnText('description')
			->setLabel('opis');

		//operacje
		$this->addColumnOperation();
	}

}
