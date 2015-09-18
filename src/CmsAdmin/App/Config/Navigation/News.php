<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\Config\Navigation;

class News extends \Mmi\Navigation\Config {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Aktualności')
				->setModule('cmsAdmin')
				->setController('news')
				->addChild(self::newElement()
					->setLabel('Dodaj')
					->setModule('cmsAdmin')
					->setController('news')
					->setAction('edit'));
	}

}
