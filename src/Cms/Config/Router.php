<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Config;

/**
 * Klasa konfiguracji routera
 */
class Router extends \Mmi\Controller\Router\Config {

	public function __construct() {

		//strona główna moduł cms + kontroler index + akcja index
		$this->setRoute('cms-index', '/^$/', ['module' => 'cms'], ['controller' => 'index', 'action' => 'index']);

		//moduł + kontroler index + akcja index np. /cms
		$this->setRoute('cms-m', '/^([a-zA-Z]+)$/', ['module' => '$1'], ['controller' => 'index', 'action' => 'index']);

		//moduł + kontroler + akcja index np. /cms/article
		$this->setRoute('cms-mc', '/^([a-zA-Z]+)\/([a-zA-Z\-]+)$/', ['module' => '$1', 'controller' => '$2'], ['action' => 'index']);

		//moduł + kontroler + akcja np. /cms/article/display
		$this->setRoute('cms-mca', '/^([a-zA-Z]+)\/([a-zA-Z\-]+)\/([a-zA-Z]+)$/', ['module' => '$1', 'controller' => '$2', 'action' => '$3']);
	}

}
