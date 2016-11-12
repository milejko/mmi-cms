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
 * Grid atrybutów w szablonie artykułu
 */
class CategoryAttributeRelationGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//domyślne zapytanie
		$this->setQuery(
			(new \Cms\Orm\CmsAttributeRelationQuery)
				->join('cms_attribute')->on('cms_attribute_id')
				->joinLeft('cms_attribute_value')->on('cms_attribute_value_id')
				->whereObject()->equals($this->getOption('object'))
				->andFieldObjectId()->equals($this->getOption('objectId'))
				->orderAscOrder()
		);

		//nazwa typu
		$this->addColumnText('cms_attribute.name')
			->setLabel('nazwa');

		//kolejność
		$this->addColumnText('order')
			->setLabel('kolejność');

		//wartość domyślna
		$this->addColumnText('cms_attribute_value.value')
			->setLabel('wartość domyślna');

		//operacje
		$this->addColumnOperation()
			->setDeleteParams(['action' => 'deleteAttributeRelation', 'relationId' => '%id%'])
			->setEditParams(['action' => 'edit', 'relationId' => '%id%']);
	}

}
