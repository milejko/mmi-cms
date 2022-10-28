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
use ReflectionClass;
use ReflectionMethod;

/**
 * Model list komponentów MVC
 */
class Reflection
{
    public const CONTROLLER_PATTERN = '/^([a-zA-Z0-9]+)\\\([a-zA-Z0-9]+)Controller$/';

    /**
     * Pobranie modułów, kontrolerów, akcji
     * @return array
     */
    public function getOptionsWildcard($minDepth = 1, $filter = '//')
    {
        $structure = [];
        //iteracja po modułach
        foreach ($this->getControllers() as $controllerClass => $controller) {
            if ($minDepth <= 1) {
                $structure['module=' . $controller['moduleName']] = $controller['moduleName'];
            }
            if ($minDepth <= 2) {
                $structure['module=' . $controller['moduleName'] . '&controller=' . $controller['controllerName']] = $controller['moduleName'] . '/' . $controller['controllerName'];
            }
            //class methods iteration
            foreach ((new ReflectionClass($controllerClass))->getMethods(ReflectionMethod::IS_PUBLIC) as $methodName) {
                //not an action
                if (!strpos($methodName->name, 'Action')) {
                    continue;
                }
                $actionName = substr($methodName->name, 0, -6);
                $comment = trim(\str_replace(['/', '*'], '', $methodName->getDocComment()));
                $structure['module=' . $controller['moduleName'] . '&controller=' . $controller['controllerName'] . '&action=' . $actionName] = $controller['moduleName'] . '/' . $controller['controllerName'] . '/' . $actionName . ' (' . $comment . ')';
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

    /**
     * Gets controller list
     */
    private function getControllers(): array
    {
        $controllers = [];
        foreach (Structure::getStructure('classes') as $class) {
            //
            if (0 == preg_match(self::CONTROLLER_PATTERN, $class, $matches)) {
                continue;
            }
            $controllers[$class] = [
                'moduleName' => \lcfirst($matches[1]),
                'controllerName' => \lcfirst($matches[2])
            ];
        }
        return $controllers;
    }
}
