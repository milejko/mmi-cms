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

/**
 * Buforowany widget kategorii CMS
 */
class CategoryWidgetDisplay extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * Render widgetu (front)
     * @param CmsCategoryWidgetCategoryRecord $widgetRelationRecord
     * @return string
     */
    public function categoryWidgetDisplay(CmsCategoryWidgetCategoryRecord $widgetRelationRecord)
    {
        //próba odczytu z bufora
        if (null === $output = \App\Registry::$cache->load($cacheKey = CmsCategoryWidgetCategoryRecord::HTML_CACHE_PREFIX . $widgetRelationRecord->id)) {
            //model widgeta
            $widgetModel =  new WidgetModel($widgetRelationRecord);
            //render szablonu
            $output = $widgetModel->displayAction($this->view);
            //bufor wyłączony parametrem
            if ($widgetModel->getWidgetConfg()->getCacheLifeTime()) {
                //zapis do bufora (czas określony parametrem)
                \App\Registry::$cache->save($output, $cacheKey, $widgetModel->getWidgetConfg()->getCacheLifeTime());
            }
        }
        //render szablonu
        return $output;
    }

}
