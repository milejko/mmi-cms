<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class NewsGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\News\Query::factory());

		$this->addColumn('text', 'dateAdd', [
			'label' => 'data dodania'
		]);

		$this->addColumn('text', 'dateModify', [
			'label' => 'data modyfikacji'
		]);

		$this->addColumn('text', 'title', [
			'label' => 'tytuł'
		]);

		$this->addColumn('text', 'text', [
			'sortable' => false,
			'label' => 'treść aktualności'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
