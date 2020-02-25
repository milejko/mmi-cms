<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

use App\Registry;
use Cms\Model\WidgetModel;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;

/**
 * Widget edycji widgeta
 */
class CategoryWidgetEdit extends \Mmi\Mvc\ViewHelper\HelperAbstract
{
    /**
     * Render edycji widgeta
     * @param CmsCategoryWidgetCategoryRecord $widgetRelation
     * @return string
     */
    public function categoryWidgetEdit(CmsCategoryWidgetCategoryRecord $widgetRelationRecord)
    {
        //render szablonu
        return (new WidgetModel($widgetRelationRecord, Registry::$config->skinset))->editAction($this->view);
    }

}