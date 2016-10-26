<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

class NavPartRoute extends \Mmi\Navigation\NavigationConfig {

	public static function getMenu() {
		return (new \Mmi\Navigation\NavigationConfigElement)
				->setLabel('Routing')
				->setModule('cmsAdmin')
				->setController('route')
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setLabel('Dodaj')
					->setModule('cmsAdmin')
					->setController('route')
					->setAction('edit')
		);
	}

}
