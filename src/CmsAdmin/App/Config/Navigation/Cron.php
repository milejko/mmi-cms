<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\Config\Navigation;

class Cron extends \Mmi\Navigation\Config {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Cron')
				->setModule('cmsAdmin')
				->setController('cron')
				->addChild(self::newElement()
					->setLabel('Dodaj')
					->setModule('cmsAdmin')
					->setController('cron')
					->setAction('edit'));
	}

}
