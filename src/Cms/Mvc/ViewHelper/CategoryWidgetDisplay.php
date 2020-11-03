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
        if (null === $output = $this->view->getCache()->load($cacheKey = CmsCategoryWidgetCategoryRecord::HTML_CACHE_PREFIX . $widgetRelationRecord->id)) {
            //model widgeta
            $widgetModel =  new WidgetModel($widgetRelationRecord, Registry::$config->skinset);
            //render szablonu
            $output = $widgetModel->renderDisplayAction($this->view);
            //bufor wyłączony parametrem
            if ($widgetModel->getWidgetConfig()->getCacheLifeTime()) {
                //zapis do bufora (czas określony parametrem)
                $this->view->getCache()->save($output, $cacheKey, $widgetModel->getWidgetConfig()->getCacheLifeTime());
            }
        }
        //render szablonu
        return $output;
    }

}
