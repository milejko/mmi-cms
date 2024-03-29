<?php

namespace Cms\Model;

use Cms\Api\DataInterface;
use Cms\Exception\CategoryWidgetException;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\AbstractWidgetController;
use Mmi\Mvc\View;
use Cms\App\CmsWidgetConfig;
use Cms\App\CmsSectionConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\App\CmsTemplateConfig;
use Mmi\App\App;
use Mmi\Cache\CacheInterface;

/**
 * Model widgeta
 */
class WidgetModel
{
    /**
     * Dane widgeta
     * @var CmsCategoryWidgetCategoryRecord
     */
    private $_cmsWidgetRecord;

    /**
     * Model skóry
     * @var SkinsetModel
     */
    private $_skinsetModel;

    /**
     * Konfiguracja widgeta
     * @var CmsWidgetConfig
     */
    private $_widgetConfig;

    /**
     * Cache object
     */
    private CacheInterface $_cacheService;

    /**
     * Konstruktor
     * @param CmsCategoryWidgetCategoryRecord $cmsWidgetRecord
     * @param CmsSkinsetConfig $skinsetConfig
     * @throws CategoryWidgetException
     */
    public function __construct(CmsCategoryWidgetCategoryRecord $cmsWidgetRecord, CmsSkinsetConfig $skinsetConfig)
    {
        //przypisywanie rekordu widgeta
        $this->_cmsWidgetRecord = $cmsWidgetRecord;
        //brak zdefiniowanego widgeta
        if (!$cmsWidgetRecord->widget) {
            throw new CategoryWidgetException('Widget type not specified');
        }
        //cache (improper) injection
        $this->_cacheService = App::$di->get(CacheInterface::class);
        //model zestawu skór
        $this->_skinsetModel = new SkinsetModel($skinsetConfig);
        //wyszukiwanie konfiguracji widgeta
        if (null === $this->_widgetConfig = $this->_skinsetModel->getWidgetConfigByKey($cmsWidgetRecord->widget)) {
            throw new CategoryWidgetException('Compatible widget not found: ' . $cmsWidgetRecord->widget);
        }
    }

    /**
     * Pobranie konfiguracji widgeta
     * @return CmsWidgetConfig
     */
    public function getWidgetConfig()
    {
        return $this->_widgetConfig;
    }

    /**
     * Pobranie konfiguracji sekcji
     * @return CmsSectionConfig
     */
    public function getSectionConfig()
    {
        return $this->_skinsetModel->getSectionConfigByKey($this->_cmsWidgetRecord->widget);
    }

    /**
     * Pobranie konfiguracji szablonu
     * @return CmsTemplateConfig
     */
    public function getTemplateConfig()
    {
        return $this->_skinsetModel->getTemplateConfigByKey($this->_cmsWidgetRecord->widget);
    }

    /**
     * Render akcji edycji
     * @param View $view
     * @return string
     * @throws CategoryWidgetException
     */
    public function renderEditAction(View $view)
    {
        //wywołanie akcji edycji
        $this->_createController()->editAction();
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/edit');
    }

    /**
     * Render akcji podglądu
     * @param View $view
     * @return string
     * @throws CategoryWidgetException
     */
    public function renderPreviewAction(View $view)
    {
        //wywołanie akcji podglądu
        $this->_createController()->previewAction();
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/preview');
    }

    /**
     * Render akcji wyświetlenia
     * @param View $view
     * @return string
     * @throws CategoryWidgetException
     */
    public function renderDisplayAction(View $view)
    {
        //wywołanie akcji wyświetlenia
        $this->_createController()->displayAction($view->request);
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/display');
    }

    /**
     * Pobiera obiekt transportowy z kontrolera (na potrzeby API)
     */
    public function getDataObject(): DataInterface
    {
        //pobranie z cache
        if (null === $dataObject = $this->_cacheService->load($cacheKey = CmsCategoryWidgetCategoryRecord::JSON_CACHE_PREFIX . $this->_cmsWidgetRecord->id)) {
            //pobranie obiektu z kontrolera
            $this->_cacheService->save($dataObject = $this->_createController()->getDataObject(), $cacheKey, $this->_widgetConfig->getCacheLifeTime());
        }
        return $dataObject;
    }

    /**
     * Wywołanie akcji usuwania
     * @param View $view
     * @return void
     * @throws CategoryWidgetException
     */
    public function invokeDeleteAction()
    {
        //wywołanie akcji wyświetlenia
        $this->_createController()->deleteAction();
    }

    /**
     * Tworzy instancję kontrolera
     * @return WidgetController
     * @throws CategoryWidgetException
     */
    private function _createController()
    {
        //getting the controller name
        $controllerName = $this->_widgetConfig->getControllerClassName();
        //missing controller
        if (!App::$di->has($controllerName)) {
            throw new CategoryWidgetException('Missing controller: ' . $controllerName);
        }
        //getting controller from the DI
        $targetController = App::$di->get($controllerName);
        //controller invalid
        if (!($targetController instanceof AbstractWidgetController)) {
            throw new CategoryWidgetException($controllerName . ' should extend \Cms\AbstractWidgetController');
        }
        //injecting category record
        $targetController
            ->setWidgetConfig($this->_widgetConfig)
            ->setWidgetRecord($this->_cmsWidgetRecord);
        //zwrot instancji kontrolera
        return $targetController;
    }

    /**
     * Pobiera prefiks nazwy szablonu
     * @return string
     */
    private function _getTemplatePrefix()
    {
        $explodedControllerClass = explode('\\', $this->_widgetConfig->getControllerClassName());
        return lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr(array_pop($explodedControllerClass), 0, -10));
    }
}
