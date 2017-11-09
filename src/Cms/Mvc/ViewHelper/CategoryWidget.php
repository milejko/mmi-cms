<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

/**
 * Buforowany widget kategorii CMS
 */
class CategoryWidget extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    CONST TEMPLATE = 'cms/mvc/view-helper/category-widget';

    /**
     * Render widgetu
     * @param \Cms\Orm\CmsCategoryWidgetCategoryRecord $widgetRelation
     * @return string
     */
    public function categoryWidget(\Cms\Orm\CmsCategoryWidgetCategoryRecord $widgetRelation)
    {
        $widgetRecord = $widgetRelation->getWidgetRecord();
        //próba odczytu z bufora
        if (null === $widgetData = \App\Registry::$cache->load($cacheKey = 'category-widget-html-' . $widgetRelation->id)) {
            //pobranie konfiguracji widgetu
            $widgetRequest = $widgetRecord->getMvcParamsAsRequest();
            //ustawienie identyfikatora relacji widgetu
            $widgetRequest->widgetId = $widgetRelation->id;
            //render widgetu
            $widgetData = \Mmi\Mvc\ActionHelper::getInstance()->action($widgetRequest);
            //bufor wyłączony parametrem
            if ($widgetRecord->cacheLifetime) {
                //zapis do bufora (czas określony parametrem)
                \App\Registry::$cache->save($widgetData, $cacheKey, $widgetRecord->cacheLifetime);
            }
        }
        //relacja do widoku
        $this->view->_widgetRelation = $widgetRelation;
        //dane html do widoku
        $this->view->_widgetData = $widgetData;
        //render szablonu
        return \Mmi\App\FrontController::getInstance()->getView()->renderTemplate(static::TEMPLATE);
    }

}
