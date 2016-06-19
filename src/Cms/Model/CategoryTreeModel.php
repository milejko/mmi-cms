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
	 * Kolekcja kategorii
	 * @var \Mmi\Orm\RecordCollection
	 */
	private $_categoryCollection;
	
	/**
	 * Konstruktor pobiera kategorie
	 */
	public function __construct() {
		//pobranie kategorii
		$this->_categoryCollection = (new CmsCategoryQuery)->find();
	}

	/**
	 * Pobiera listę kategorii
	 * @return array
	 */
	public function getCategoriesFlat() {
		return (new CmsCategoryQuery)
				->findPairs('id', 'name');
	}

}
