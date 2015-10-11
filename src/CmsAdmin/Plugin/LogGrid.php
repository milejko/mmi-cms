<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

use CmsAdmin\Grid\Grid;
use CmsAdmin\Grid\Element;

/**
 * Klasa grid loga CMS
 */
class LogGrid extends Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\CmsLogQuery::factory()
				->orderDescDateTime());

		$this->addElementIndex();

		$this->addElementText('dateTime')
			->setLabel('data i czas');

		$this->addElementText('operation')
			->setLabel('operacja');

		$this->addElementText('url')
			->setLabel('URL');

		$this->addElementText('data')
			->setLabel('dane');

		$this->addElementText('ip')
			->setLabel('adres IP');

		$this->addElementCustom('ids')
			->setLabel('xyz')
			->setTemplateCode('{$record->id}');
		
		$this->addElementOperation();
		
		$this->addElementCheckbox('success')
			->setLabel('sukces');
	}

}
