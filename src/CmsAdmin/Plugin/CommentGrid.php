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
 * Grid komentarzy
 */
class CommentGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(\Cms\Orm\CmsCommentQuery::factory());

		//indeks
		$this->addColumnIndex();

		//data dodania
		$this->addColumnText('dateAdd')
			->setLabel('data dodania');

		//komentarz
		$this->addColumnText('text')
			->setLabel('komentarz');

		//podpis
		$this->addColumnText('signature')
			->setLabel('podpis');

		//zasób
		$this->addColumnText('object')
			->setLabel('zasób');

		//id zasobu
		$this->addColumnText('objectId')
			->setLabel('id zasobu');

		//operacje bez edycji
		$this->addColumnOperation()
			->setEditParams([]);
	}

}
