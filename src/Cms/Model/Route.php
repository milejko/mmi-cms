<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

class Route {

	/**
	 * Aktualizuje konfigurację routera
	 * @param \Mmi\Mvc\RouterConfig $config
	 * @param \Mmi\Orm\RecordCollection $routes
	 * @return \Mmi\Mvc\RouterConfig
	 */
	public static function updateRouterConfig(\Mmi\Mvc\RouterConfig $config, \Mmi\Orm\RecordCollection $routes) {
		$i = 0;
		$previousRoutes = $config->getRoutes();
		$resultRoutes = [];
		foreach ($routes as $route) { /* @var $route \Cms\Orm\RouteRecord */
			$i++;
			$route = $route->toRouteArray();
			$routeConfig = new \Mmi\Mvc\RouterConfigRoute();
			$routeConfig->default = $route['default'];
			$routeConfig->replace = $route['replace'];
			$routeConfig->pattern = $route['pattern'];
			$routeConfig->name = 'cms-' . $i;
			$resultRoutes[$i] = $routeConfig;
		}
		return $config->setRoutes(array_merge($resultRoutes, $previousRoutes), true);
	}

}
