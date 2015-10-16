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
 * Klasa grid loga CMS
 */
class LogGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//domyślnie posortowane po dacie i czasie
		$this->setQuery((new \Cms\Orm\CmsLogQuery)
				->orderDescDateTime());

		//data i czas
		$this->addColumnText('dateTime')
			->setLabel('data i czas');

		//operacja
		$this->addColumnText('operation')
			->setLabel('operacja');

		//url
		$this->addColumnText('url')
			->setLabel('URL');

		//dane
		$this->addColumnText('data')
			->setLabel('dane');

		//ip
		$this->addColumnText('ip')
			->setLabel('adres IP');

		//sukces
		$this->addColumnCheckbox('success')
			->setLabel('sukces');
	}

}
