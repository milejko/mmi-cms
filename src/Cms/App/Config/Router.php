<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App\Config;

/**
 * Klasa konfiguracji routera
 */
class Router extends \Mmi\Controller\Router\Config {

	public function __construct() {

		//moduł + kontroler index + akcja index np. /news
		$this->setRoute('cms-module', '/^([a-zA-Z]+)$/', ['module' => '$1'], ['controller' => 'index', 'action' => 'index']);

		//moduł + kontroler + akcja index np. /cms/article
		$this->setRoute('cms-module-controller', '/^([a-zA-Z]+)\/([a-zA-Z\-]+)$/', ['module' => '$1', 'controller' => '$2'], ['action' => 'index']);

		//moduł + kontroler + akcja np. /cms/article/display
		$this->setRoute('cms-module-controller-action', '/^([a-zA-Z]+)\/([a-zA-Z\-]+)\/([a-zA-Z]+)$/', ['module' => '$1', 'controller' => '$2', 'action' => '$3']);
	}

}
