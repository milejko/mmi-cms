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

		$this->addElement((new Element\IndexElement()));

		$this->addElement((new Element\TextElement('dateTime'))
				->setLabel('data i czas'));

		$this->addElement((new Element\TextElement('operation'))
				->setLabel('operacja'));

		$this->addElement((new Element\TextElement('url'))
				->setLabel('URL'));

		$this->addElement((new Element\TextElement('data'))
				->setLabel('dane'));

		$this->addElement((new Element\TextElement('ip'))
				->setLabel('adres IP'));

		$this->addElement((new Element\CheckboxElement('success'))
				->setLabel('sukces'));
	}

}
