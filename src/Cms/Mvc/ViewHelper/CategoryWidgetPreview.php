<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

use Cms\App\CmsSkinsetConfig;
use Cms\Model\WidgetModel;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Mmi\App\App;

/**
 * Widget podglądu widgeta
 */
class CategoryWidgetPreview extends \Mmi\Mvc\ViewHelper\HelperAbstract
{
    /**
     * Render edycji widgeta
     * @param CmsCategoryWidgetCategoryRecord $widgetRelation
     * @return string
     */
    public function categoryWidgetPreview(CmsCategoryWidgetCategoryRecord $widgetRelationRecord)
    {
        //render szablonu
        return (new WidgetModel($widgetRelationRecord, App::$di->get(CmsSkinsetConfig::class)))->renderPreviewAction($this->view);
    }

}