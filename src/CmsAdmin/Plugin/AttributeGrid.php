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
 * Grid atrybutów
 */
class AttributeGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(new \Cms\Orm\CmsAttributeQuery);

		//nazwa atrybutu
		$this->addColumnText('name')
			->setLabel('nazwa');

		//klucz atrybutu
		$this->addColumnText('key')
			->setLabel('klucz');

		//opis
		$this->addColumnText('description')
			->setLabel('opis');

		//klasa pola
		$this->addColumnSelect('cmsAttributeTypeId')
			->setLabel('klasa pola')
			->setMultioptions((new \Cms\Orm\CmsAttributeTypeQuery)
				->orderAscName()
				->findPairs('id', 'name'));

		//waga
		//$this->addColumnText('indexWeight')
		//	->setLabel('waga w indeksie');

		//operacje
		$this->addColumnOperation();
	}

}
