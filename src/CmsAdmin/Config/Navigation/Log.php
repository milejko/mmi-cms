<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Config\Navigation;

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
