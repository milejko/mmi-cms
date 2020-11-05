<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Model;

use Mmi\Mvc\Structure;

/**
 * Model list komponentów MVC
 */
class Reflection
{

    /**
     * Pobranie modułów, kontrolerów, akcji
     * @return array
     */
    public static function getOptionsWildcard($minDepth = 1, $filter = '//')
    {
        $structure = [];
        //iteracja po modułach
        foreach (Structure::getStructure('module') as $moduleName => $module) {
            throw new Exception('@TODO: implement reflecting actions');
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
        //filtracja (odrzucanie wpisów które nie pasują)
        foreach ($structure as $k => $v) {
            if (!preg_match($filter, $k)) {
                unset($structure[$k]);
            }
        }
        //sortowanie alfabetyczne
        ksort($structure);
        return $structure;
    }

}
