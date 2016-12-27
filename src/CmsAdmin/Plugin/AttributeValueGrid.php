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
 * Grid wartości atrybutów
 */
class AttributeValueGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(new \Cms\Orm\CmsAttributeValueQuery);
		$attributeQuery = new \Cms\Orm\CmsAttributeQuery;

		//zapytanie filtrowane ID atrybutu
		if ($this->getOption('id')) {
			$this->getQuery()
				->whereCmsAttributeId()->equals($this->getOption('id'));
			$attributeQuery->whereId()->equals($this->getOption('id'));
		}
		
		//wartość
		$this->addColumnText('value')
			->setLabel('wartość');
		
		//etykieta
		$this->addColumnText('label')
			->setLabel('etykieta');
		
		//operacje
		$this->addColumnOperation()
			->setEditParams(['module' => 'cmsAdmin', 'controller' => 'attribute', 'action' => 'edit', 'id' => '%cmsAttributeId%', 'valueId' => '%id%'])
			->setDeleteParams(['module' => 'cmsAdmin', 'controller' => 'attributeValue', 'action' => 'delete', 'id' => '%id%']);
	}

}
