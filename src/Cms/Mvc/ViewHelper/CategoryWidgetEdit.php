<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

use Cms\Model\WidgetModel;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\WidgetController;

/**
 * Widget edycji widgeta
 */
class CategoryWidgetEdit extends \Mmi\Mvc\ViewHelper\HelperAbstract
{
    /**
     * Render edycji widgetu
     * @param CmsCategoryWidgetCategoryRecord $widgetRelation
     * @return string
     */
    public function categoryWidgetEdit(CmsCategoryWidgetCategoryRecord $widgetRelationRecord)
    {
        $widgetConfig = (new WidgetModel($widgetRelationRecord))->getWidgetConfg();
        //odczytywanie nazwy kontrolera
        $controllerClass = $widgetConfig->getControllerClassName();
        //powołanie kontrolera z rekordem relacji
        $targetController = new $controllerClass($this->view->request, $this->view, $widgetRelationRecord);
        //kontroler nie jest poprawny
        if (!($targetController instanceof WidgetController)) {
            return 'Not a WidgetController';
        }
        //wywołanie akcji
        $targetController->editAction();
        $explodedControllerClass = explode('\\', $controllerClass);
        //render szablonu
        return $this->view->renderTemplate(lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr($explodedControllerClass[1], 0, -10)) . '/edit');
    }

}