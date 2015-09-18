<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

class Route {

	/**
	 * Aktualizuje konfigurację routera
	 * @param \Mmi\Controller\Router\Config $config
	 * @param \Mmi\Orm\Record\Collection $routes
	 * @return \Mmi\Controller\Router\Config
	 */
	public static function updateRouterConfig(\Mmi\Controller\Router\Config $config, \Mmi\Orm\Record\Collection $routes) {
		$i = 0;
		foreach ($routes as $route) { /* @var $route \Cms\Orm\Route\Record */
			$i++;
			$route = $route->toRouteArray();
			$config->setRoute('cms-' . $i, $route['pattern'], $route['replace'], $route['default']);
		}
		return $config;
	}

}
