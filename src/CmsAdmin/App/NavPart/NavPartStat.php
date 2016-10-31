<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

class NavPartStat extends \Mmi\Navigation\NavigationConfig {

	public static function getMenu() {
		return (new \Mmi\Navigation\NavigationConfigElement)
				->setLabel('Statystyki')
				->setModule('cmsAdmin')
				->setController('stat')
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setLabel('Nazwy')
					->setModule('cmsAdmin')
					->setController('stat')
					->setAction('label')
					->addChild((new \Mmi\Navigation\NavigationConfigElement)
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('stat')
						->setAction('edit')));
	}

}
