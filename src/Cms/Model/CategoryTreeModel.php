<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCategoryQuery,
	Cms\Orm\CmsCategoryRecord;

/**
 * Model kategorii
 */
class CategoryTreeModel {

	/**
	 * Pobiera listę kategorii
	 * @return array
	 */
	public function getCategoriesFlat() {
		return (new CmsCategoryQuery)
				->findPairs('id', 'name');
	}

}
