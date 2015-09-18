<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class ArticleGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Article\Query::factory());

		$this->addColumn('text', 'title', [
			'label' => 'tytuł',
		]);

		$this->addColumn('text', 'text', [
			'label' => 'treść',
			'sortable' => false,
			'seekable' => false
		]);

		$this->addColumn('text', 'dateAdd', [
			'label' => 'data dodania'
		]);

		$this->addColumn('text', 'dateModify', [
			'label' => 'data modyfikacji'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
