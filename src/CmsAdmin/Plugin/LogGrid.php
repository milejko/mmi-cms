<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

class LogGrid extends \CmsAdmin\Plugin\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\CmsLogQuery::factory()
				->orderDescDateTime());

		$this->addColumn('text', 'dateTime', [
			'label' => 'data i czas'
		]);
		$this->addColumn('text', 'operation', [
			'label' => 'operacja'
		]);
		$this->addColumn('text', 'url', [
			'label' => 'URL'
		]);

		$this->addColumn('text', 'data', [
			'label' => 'dane',
		]);

		$this->addColumn('text', 'ip', [
			'label' => 'adres IP'
		]);
		$this->addColumn('checkbox', 'success', [
			'label' => 'sukces',
		]);
	}

}
