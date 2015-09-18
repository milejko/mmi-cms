<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class StatLabelGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Stat\Label\Query::factory());

		$this->addColumn('text', 'object', [
			'label' => 'klucz'
		]);

		$this->addColumn('text', 'label', [
			'label' => 'nazwa statystyki'
		]);

		$this->addColumn('text', 'description', [
			'label' => 'opis'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje',
			['links' => ['remove' => null]]
		]);
	}

}
