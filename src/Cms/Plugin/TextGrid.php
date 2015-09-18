<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class TextGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Text\Query::lang()
				->orderAscKey());

		$this->setOption('rows', 100);

		$this->addColumn('text', 'lang', [
			'label' => 'język'
		]);

		$this->addColumn('text', 'key', [
			'label' => 'klucz',
		]);

		$this->addColumn('text', 'content', [
			'label' => 'treść',
			'sortable' => false,
			'seekable' => false
		]);

		$this->addColumn('text', 'dateModify', [
			'label' => 'data modyfikacji',
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
