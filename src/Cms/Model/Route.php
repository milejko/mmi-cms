<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

class Route {

	/**
	 * Aktualizuje konfigurację routera
	 * @param \Mmi\Mvc\Router\Config $config
	 * @param \Mmi\Orm\RecordCollection $routes
	 * @return \Mmi\Mvc\Router\Config
	 */
	public static function updateRouterConfig(\Mmi\Mvc\Router\Config $config, \Mmi\Orm\RecordCollection $routes) {
		$i = 0;
		foreach ($routes as $route) { /* @var $route \Cms\Orm\RouteRecord */
			$i++;
			$route = $route->toRouteArray();
			$config->setRoute('cms-' . $i, $route['pattern'], $route['replace'], $route['default']);
		}
		return $config;
	}

}
