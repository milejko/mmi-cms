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
use Mmi\Cache\CacheInterface;
use Mmi\Mvc\View;

/**
 * Buforowany widget kategorii CMS
 */
class CategoryWidgetDisplay extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var CmsSkinsetConfig
     */
    private $cmsSkinsetConfig;

    public function __construct(
        View $view,
        CacheInterface $cache,
        CmsSkinsetConfig $cmsSkinsetConfig
    )
    {
        $this->cache            = $cache;
        $this->cmsSkinsetConfig = $cmsSkinsetConfig;
        parent::__construct($view);
    }

    /**
     * Render widgetu (front)
     * @param CmsCategoryWidgetCategoryRecord $widgetRelationRecord
     * @return string
     */
    public function categoryWidgetDisplay(CmsCategoryWidgetCategoryRecord $widgetRelationRecord)
    {
        //próba odczytu z bufora
        if (null === $output = $this->cache->load($cacheKey = CmsCategoryWidgetCategoryRecord::HTML_CACHE_PREFIX . $widgetRelationRecord->id)) {
            //model widgeta
            $widgetModel =  new WidgetModel($widgetRelationRecord, $this->cmsSkinsetConfig);
            //render szablonu
            $output = $widgetModel->renderDisplayAction($this->view);
            //bufor wyłączony parametrem
            if ($widgetModel->getWidgetConfig()->getCacheLifeTime()) {
                //zapis do bufora (czas określony parametrem)
                $this->cache->save($output, $cacheKey, $widgetModel->getWidgetConfig()->getCacheLifeTime());
            }
        }
        //render szablonu
        return $output;
    }

}
