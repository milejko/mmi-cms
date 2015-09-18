<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\Config\Navigation;

class Stat extends \Mmi\Navigation\Config {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Statystyki')
				->setModule('cmsAdmin')
				->setController('stat')
				->addChild(self::newElement()
					->setLabel('Nazwy')
					->setModule('cmsAdmin')
					->setController('stat')
					->setAction('label')
					->addChild(self::newElement()
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('stat')
						->setAction('edit')));
	}

}
