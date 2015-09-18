<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\Config\Navigation;

class Log extends \Mmi\Navigation\Config {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Log systemowy')
				->setModule('cmsAdmin')
				->setController('log')
				->addChild(self::newElement()
					->setLabel('Błedy')
					->setModule('cmsAdmin')
					->setController('log')
					->setAction('error'));
	}

}
