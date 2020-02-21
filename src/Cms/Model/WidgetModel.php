<?php

namespace Cms\Model;

use App\Registry;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Mmi\App\KernelException;

class WidgetModel
{
    /**
     * Dane widgeta
     * @var CmsCategoryWidgetCategoryRecord
     */
    private $_cmsWidgetRecord;

    /**
     * Konfiguracja widgetas
     * @var CmsWidgetConfig
     */
    private $_widgetConfig;

    public function __construct(CmsCategoryWidgetCategoryRecord $cmsWidgetRecord)
    {
        $this->_cmsWidgetRecord = $cmsWidgetRecord;
        //brak zdefiniowanego widgeta
        if (!$cmsWidgetRecord->widget) {
            throw new KernelException('Widget type not specified');
        }
        //wyszukiwanie szablonu
        if (!($template = $cmsWidgetRecord->getCategoryRecord()->template)) {
            throw new KernelException('Category template not specified');
        }
        //iteracja po dostępnych skórach
        foreach (Registry::$config->skinset->getSkins() as $skin) {
            $skinModel = new SkinModel($skin);
            //w skórze nie ma tego szablonu
            if (!$skinModel->templateExists($template)) {
                continue;
            }
            //wyszukiwanie widgeta
            $this->_widgetConfig = $skinModel->getWidgetByKey($cmsWidgetRecord->widget);
        }
        if (!isset($this->_widgetConfig)) {
            throw new KernelException('Compatible widget not found');
        }
    }

    /**
     * Pobranie konfiguracji widgeta
     * @return CmsWidgetConfig
     */
    public function getWidgetConfg()
    {
        return $this->_widgetConfig;
    }

}