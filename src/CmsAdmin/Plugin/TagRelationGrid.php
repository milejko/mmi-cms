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
 * Grid tagów
 */
class TagRelationGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery((new \Cms\Orm\CmsTagRelationQuery)
				->join('cms_tag')->on('cms_tag_id')
		);
		
		//nazwa taga
		$this->addColumnText('cms_tag.tag')
			->setLabel('tag');
		
		//obiekt
		$this->addColumnText('object')
			->setLabel('zasób');
		
		//id obiektu
		$this->addColumnText('objectId')
			->setLabel('ID zasobu');

		//operacje
		$this->addColumnOperation();
	}

}
