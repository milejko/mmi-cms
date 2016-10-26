<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

class NavPartText extends \Mmi\Navigation\NavigationConfig {

	public static function getMenu() {
		return (new \Mmi\Navigation\NavigationConfigElement)
				->setLabel('Teksty stałe')
				->setModule('cmsAdmin')
				->setController('text')
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setLabel('Dodaj')
					->setModule('cmsAdmin')
					->setController('text')
					->setAction('edit'));
	}

}
