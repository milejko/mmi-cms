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
 * Grid harmonogramu
 */
class CronGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(new \Cms\Orm\CmsCronQuery);

		//nazwa
		$this->addColumnText('name')
			->setLabel('nazwa');

		//opis
		$this->addColumnText('description')
			->setLabel('opis');

		//crontab
		$this->addColumnCustom('crontab')
			->setLabel('crontab')
			->setTemplateCode('{$record->minute} {$record->hour} {$record->dayOfMonth} {$record->month} {$record->dayOfWeek}');

		//data dodania
		$this->addColumnText('dateAdd')
			->setLabel('data dodania');

		//ostatnie wywołanie
		$this->addColumnText('dateLastExecute')
			->setLabel('ostatnie wywołanie');

		//aktywny
		$this->addColumnCheckbox('active')
			->setLabel('włączony');

		//operacje
		$this->addColumnOperation();
	}

}
