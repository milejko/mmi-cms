<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App;

/**
 * Konfiguracja nawigatora
 */
class CmsNavigationConfig extends \Mmi\Navigation\NavigationConfig {

	/**
	 * Pobiera menu
	 * @return \Mmi\Navigation\NavigationConfigElement
	 */
	public static function getMenu() {
		return (new \Mmi\Navigation\NavigationConfigElement)
				->setLabel('Panel administracyjny')
				->setModule('cmsAdmin')
				->setController('index')
				->addChild(self::_getContentPart());
	}

	/**
	 * Pobiera część kontentową
	 * @return \Mmi\Navigation\NavigationConfigElement
	 */
	protected static function _getContentPart() {
		return (new \Mmi\Navigation\NavigationConfigElement)
				->setLabel('CMS')
				->setModule('cmsAdmin')
				->addChild(self::_getAdminPart())
				->addChild(NavPart\NavPartAttribute::getMenu())
				->addChild(NavPart\NavPartComment::getMenu())
				->addChild(NavPart\NavPartContact::getMenu())
				->addChild(NavPart\NavPartFile::getMenu())
				->addChild(NavPart\NavPartStat::getMenu())
				->addChild(NavPart\NavPartTag::getMenu())
				->addChild(NavPart\NavPartText::getMenu())
				->addChild(NavPart\NavPartCategory::getMenu())
				->addChild(NavPart\NavPartAuth::getMenu())
		;
	}

	/**
	 * Pobiera część administracyjną
	 * @return \Mmi\Navigation\NavigationConfigElement
	 */
	protected static function _getAdminPart() {
		return (new \Mmi\Navigation\NavigationConfigElement)
				->setLabel('Administracja')
				->setModule('cmsAdmin')
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setModule('cmsAdmin')
					->setController('index')
					->setAction('password')
					->setLabel('Zmiana hasła'))
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setModule('cmsAdmin')
					->setController('index')
					->setAction('login')
					->setLabel('Logowanie CMS')
					->setVisible(false))
				->addChild(NavPart\NavPartCron::getMenu())
				->addChild(NavPart\NavPartLog::getMenu())
				->addChild(NavPart\NavPartMail::getMenu())
				->addChild(NavPart\NavPartAcl::getMenu());
	}

}
