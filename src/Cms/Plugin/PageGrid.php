<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class PageGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Page\Query::factory()
				->join('cms_navigation')->on('cms_navigation_id')
				->orderAscId());

		$this->addColumn('text', 'name', [
			'label' => 'nazwa',
		]);

		$this->addColumn('text', 'cms_navigation:title', [
			'label' => 'tytuł',
		]);

		$this->addColumn('text', 'dateAdd', [
			'label' => 'data dodania',
		]);

		$this->addColumn('text', 'dateModify', [
			'label' => 'data zmiany',
		]);

		$this->addColumn('checkbox', 'active', [
			'label' => 'aktywna',
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
