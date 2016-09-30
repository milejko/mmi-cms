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
	 * Pobranie modułów, kontrolerów, akcji
	 * @return array
	 */
	public static function getOptionsWildcard($minDepth = 1) {
		$structure = [];
		//iteracja po modułach
		foreach (FrontController::getInstance()->getStructure('module') as $moduleName => $module) {
			if ($minDepth <= 1) {
				$structure['module=' . $moduleName] = $moduleName;
			}
			//iteracja po kontrolerach
			foreach ($module as $controllerName => $controller) {
				if ($minDepth <= 2) {
					$structure['module=' . $moduleName . '&controller=' . $controllerName] = $moduleName . ' / ' . $controllerName;
				}
				//iteracja po akcjach
				foreach ($controller as $actionName => $action) {
					$structure['module=' . $moduleName . '&controller=' . $controllerName . '&action=' . $actionName] = $moduleName . ' / ' . $controllerName . ' / ' . $actionName;
				}
			}
		}
		return $structure;
	}

}
