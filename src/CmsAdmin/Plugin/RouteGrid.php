<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

class RouteGrid extends \CmsAdmin\Plugin\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Route\Query::factory()
				->orderAscOrder());

		$this->setOption('rows', 100);

		$this->addColumn('text', 'pattern', [
			'label' => 'wzorzec',
		]);

		$this->addColumn('text', 'replace', [
			'label' => 'tabela zamian',
		]);

		$this->addColumn('text', 'default', [
			'label' => 'tabela wartości domyślnych',
		]);

		$this->addColumn('text', 'order', [
			'label' => 'indeks kolejności',
		]);

		$this->addColumn('checkbox', 'active', [
			'label' => 'aktywna',
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
