<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Model;

/**
 * @TODO do refaktoryzacji - brzydki kod
 */
class Reflection {

	public static function getActions() {
		$structure = [];
		foreach (glob(BASE_PATH . '/src/*') as $module) {
			$moduleName = substr($module, strrpos($module, '/') + 1);
			foreach (glob($module . '/Controller/*.php') as $controller) {
				$var = file_get_contents($controller);
				$controllerName = substr($controller, strrpos($controller, '/') + 1, -4);
				if (preg_match_all('/function ([a-zA-Z0-9]+Action)\(/', $var, $actions) && isset($actions[1])) {
					foreach ($actions[1] as $action) {
						$action = substr($action, 0, -6);
						$moduleName = lcfirst($moduleName);
						$controllerName = lcfirst($controllerName);
						$structure[] = [
							'path' => trim($moduleName . '_' . $controllerName . '_' . $action, '_ '),
							'module' => $moduleName,
							'controller' => $controllerName,
							'action' => $action
						];
					}
				}
			}
		}
		return $structure;
	}

	public static function getOptionsWildcard() {
		$structure = [];
		foreach (glob(BASE_PATH . '/src/*') as $module) {
			$moduleName = substr($module, strrpos($module, '/') + 1);
			foreach (array_merge(glob($module . '/Controller/*.php'), glob($module . '/Controller/Admin/*.php')) as $controller) {
				$var = file_get_contents($controller);
				$controller = str_replace('/Admin/', '/Admin-', $controller);
				$controllerName = substr($controller, strrpos($controller, '/') + 1, -4);
				if (preg_match_all('/function ([a-zA-Z0-9]+Action)\(/', $var, $actions) && isset($actions[1])) {
					$first = true;
					foreach ($actions[1] as $action) {
						$action = substr($action, 0, -6);
						if ($first) {
							$structure[$moduleName] = $moduleName;
							$structure[$moduleName . ':' . $controllerName] = $moduleName . ' - ' . $controllerName;
							$first = false;
						}
						$structure[$moduleName . ':' . $controllerName . ':' . $action] = $moduleName . ' - ' . $controllerName . ' - ' . $action;
					}
				}
			}
		}
		return $structure;
	}

}
