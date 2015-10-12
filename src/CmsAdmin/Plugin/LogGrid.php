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
		$this->setQuery(\Cms\Orm\CmsLogQuery::factory()
				->orderDescDateTime());

		//indeks
		$this->addElementIndex();

		//data i czas
		$this->addElementText('dateTime')
			->setLabel('data i czas');

		//operacja
		$this->addElementText('operation')
			->setLabel('operacja');

		//url
		$this->addElementText('url')
			->setLabel('URL');

		//dane
		$this->addElementText('data')
			->setLabel('dane');

		//ip
		$this->addElementText('ip')
			->setLabel('adres IP');

		//sukces
		$this->addElementCheckbox('success')
			->setLabel('sukces');
	}

}
