<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Model;

use Mmi\App\FrontController;

/**
 * Model list komponentów MVC
 */
class Reflection {

	/**
	 * Pobranie akcji
	 * @return array
	 */
	public static function getActions() {
		$structure = [];
		foreach (FrontController::getInstance()->getStructure('module') as $moduleName => $module) {
			foreach ($module as $controllerName => $controller) {
				foreach ($controller as $actionName => $action) {
					$structure[] = [
						'path' => trim($moduleName . '_' . $controllerName . '_' . $actionName, '_ '),
						'module' => $moduleName,
						'controller' => $controllerName,
						'action' => $actionName
					];
				}
			}
		}
		return $structure;
	}

	/**
	 * Pobranie modułów, kontrolerów, akcji
	 * @return array
	 */
	public static function getOptionsWildcard() {
		$structure = [];
		foreach (FrontController::getInstance()->getStructure('module') as $moduleName => $module) {
			$structure[$moduleName] = $moduleName;
			foreach ($module as $controllerName => $controller) {
				$structure[$moduleName . ':' . $controllerName] = $moduleName . ' - ' . $controllerName;
				foreach ($controller as $actionName => $action) {
					$structure[$moduleName . ':' . $controllerName . ':' . $actionName] = $moduleName . ' - ' . $controllerName . ' - ' . $actionName;
				}
			}
		}
		return $structure;
	}

}
