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
class CategoryTypeAttributeRelationGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//domyślne zapytanie
		$this->setQuery(
			(new \Cms\Orm\CmsAttributeRelationQuery)
				->join('cms_attribute')->on('cms_attribute_id')
				->joinLeft('cms_attribute_value')->on('cms_attribute_value_id')
				->whereObject()->equals('cmsCategoryType')
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
			->setDeleteParams(['module' => 'cmsAdmin', 'controller' => 'categoryType', 'action' => 'deleteAttributeRelation', 'relationId' => '%id%'])
			->setEditParams(['module' => 'cmsAdmin', 'controller' => 'categoryType', 'action' => 'edit', 'relationId' => '%id%']);
	}

}
