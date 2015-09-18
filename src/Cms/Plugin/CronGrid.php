<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Plugin;

class CronGrid extends \Mmi\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Cron\Query::factory());

		$this->addColumn('text', 'name', [
			'label' => 'Nazwa',
		]);

		$this->addColumn('text', 'description', [
			'label' => 'Opis',
		]);

		$this->addColumn('custom', 'Cron', [
			'label' => 'Cron',
			'value' => '{$rowData->minute} {$rowData->hour} {$rowData->dayOfMonth} {$rowData->month} {$rowData->dayOfWeek}'
		]);
		$this->addColumn('custom', 'Object', [
			'label' => 'Wywołanie',
			'value' => '{$rowData->module}: {$rowData->controller} - {$rowData->action}'
		]);
		$this->addColumn('text', 'dateAdd', [
			'label' => 'Data dodania',
		]);
		$this->addColumn('text', 'dateLastExecute', [
			'label' => 'Ostatnie wywołanie',
		]);

		$this->addColumn('checkbox', 'active', [
			'label' => 'Włączony',
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje',
		]);
	}

}
