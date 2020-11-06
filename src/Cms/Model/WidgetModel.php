<?php

namespace Cms\Model;

use Cms\Exception\CategoryWidgetException;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\WidgetController;
use Mmi\Mvc\View;
use Cms\App\CmsWidgetConfig;
use Cms\App\CmsSectionConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\App\CmsTemplateConfig;
use Mmi\App\App;
use Mmi\Http\Request;
use Mmi\Http\Response;

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
     * Konstruktor
     * @param CmsCategoryWidgetCategoryRecord $cmsWidgetRecord
     * @param CmsSkinsetConfig $skinsetConfig
     */
    public function __construct(CmsCategoryWidgetCategoryRecord $cmsWidgetRecord, CmsSkinsetConfig $skinsetConfig)
    {
        //przypisywanie rekordu widgeta
        $this->_cmsWidgetRecord = $cmsWidgetRecord;
        //brak zdefiniowanego widgeta
        if (!$cmsWidgetRecord->widget) {
            throw new CategoryWidgetException('Widget type not specified');
        }
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
     */
    public function renderEditAction(View $view)
    {
        //wywołanie akcji edycji
        $controller = $this->_createController($view);
        $controller->editAction($view->request);
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/edit');
    }

    /**
     * Render akcji podglądu
     * @param View $view
     * @return string
     */
    public function renderPreviewAction(View $view)
    {
        //wywołanie akcji podglądu
        $controller = $this->_createController($view);
        $controller->previewAction($view->request);
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/preview');
    }

    /**
     * Render akcji wyświetlenia
     * @param View $view
     * @return string
     */
    public function renderDisplayAction(View $view)
    {
        //wywołanie akcji wyświetlenia
        $controller = $this->_createController($view);
        $controller->displayAction($view->request);
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/display');
    }

    /**
     * Wywołanie akcji usuwania
     * @param View $view
     * @return void
     */
    public function invokeDeleteAction(View $view)
    {
        //wywołanie akcji wyświetlenia
        $controller = $this->_createController($view);
        $controller->deleteAction($view->request);
    }

    /**
     * Tworzy instancję kontrolera
     * @return WidgetController
     */
    private function _createController(View $view)
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
        if (!($targetController instanceof WidgetController)) {
            throw new CategoryWidgetException($controllerName . ' should extend \Cms\WidgetController');
        }
        //injecting category record
        $targetController->setWidgetRecord($this->_cmsWidgetRecord);
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
        return lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr($explodedControllerClass[1], 0, -10));
    }

}