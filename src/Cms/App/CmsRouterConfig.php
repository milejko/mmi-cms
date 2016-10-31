<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

/**
 * Klasa konfiguracji routera
 */
class CmsRouterConfig extends \Mmi\Mvc\RouterConfig {

	public function __construct() {
		//moduł - cmsAdmin
		$this->setRoute('cms-admin-module', 'cmsAdmin', ['module' => 'cmsAdmin'], ['controller' => 'index', 'action' => 'index']);

		//moduł + kontroler + akcja index np. /cmsAdmin/text
		$this->setRoute('cms-admin-module-controller', '/^cmsAdmin\/([a-zA-Z\-]+)$/', ['module' => 'cmsAdmin', 'controller' => '$1'], ['action' => 'index']);

		//moduł + kontroler + akcja np. /cmsAdmin/text/display
		$this->setRoute('cms-admin-module-controller-action', '/^cmsAdmin\/([a-zA-Z\-]+)\/([a-zA-Z]+)$/', ['module' => 'cmsAdmin', 'controller' => '$1', 'action' => '$2']);

		//routa do stron cms i kategorii
		$this->setRoute('cms-category-dispatch', '/^([a-zA-Z0-9\/\-]+)$/', ['module' => 'cms', 'controller' => 'category', 'action' => 'dispatch', 'uri' => '$1']);
	}

}
