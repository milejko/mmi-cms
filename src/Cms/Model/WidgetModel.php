<?php

namespace Cms\Model;

use App\Registry;
use Cms\Exception\CategoryWidgetException;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\WidgetController;
use Mmi\App\KernelException;
use Mmi\Mvc\View;
use Cms\App\CmsWidgetConfig;
use Cms\App\CmsSectionConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\App\CmsTemplateConfig;

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
     * @var SkinModel
     */
    private $_skinModel;

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
            throw new KernelException('Widget type not specified');
        }
        if (null === $this->_skinModel = (new SkinsetModel($skinsetConfig))->getSkinModelByKey($cmsWidgetRecord->widget)) {
            throw new KernelException('Compatible widget not found: ' . $cmsWidgetRecord->widget);
        }
    }

    /**
     * Pobranie konfiguracji widgeta
     * @return CmsWidgetConfig
     */
    public function getWidgetConfig()
    {
        return $this->_skinModel->getWidgetConfigByKey($this->_cmsWidgetRecord->widget);
    }

    /**
     * Pobranie konfiguracji sekcji
     * @return CmsSectionConfig
     */
    public function getSectionConfig()
    {
        return $this->_skinModel->getSectionConfigByKey($this->_cmsWidgetRecord->widget);
    }

    /**
     * Pobranie konfiguracji szablonu
     * @return CmsTemplateConfig
     */
    public function getTemplateConfig()
    {
        return $this->_skinModel->getTemplateConfigByKey($this->_cmsWidgetRecord->widget);
    }

    /**
     * Wywołanie akcji edycji
     * @param View $view
     * @return string
     */
    public function editAction(View $view)
    {

        //wywołanie akcji edycji
        $controller = $this->_createController($view);
        $controller->editAction();
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/edit');
    }

    /**
     * Wywołanie akcji podglądu
     * @param View $view
     * @return string
     */
    public function previewAction(View $view)
    {
        //wywołanie akcji podglądu
        $controller = $this->_createController($view);
        $controller->previewAction();
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/preview');
    }

    /**
     * Wywołanie akcji wyświetlenia
     * @param View $view
     * @return string
     */
    public function displayAction(View $view)
    {
        //wywołanie akcji wyświetlenia
        $controller = $this->_createController($view);
        $controller->displayAction();
        //render szablonu
        return $view->renderTemplate($this->_getTemplatePrefix() . '/display');
    }

    /**
     * Tworzy instancję kontrolera
     * @return WidgetController
     */
    private function _createController(View $view)
    {
        //odczytywanie nazwy kontrolera
        $controllerClass = $this->_skinModel->getWidgetConfigByKey($this->_cmsWidgetRecord->widget)->getControllerClassName();
        //powołanie kontrolera z rekordem relacji
        $targetController = new $controllerClass($view->request, $view, $this->_cmsWidgetRecord);
        //kontroler nie jest poprawny
        if (!($targetController instanceof WidgetController)) {
            throw new CategoryWidgetException('Not an instance of WidgetController');
        }
        //zwrot instancji kontrolera
        return $targetController;        
    }

    /**
     * Pobiera prefiks nazwy szablonu
     * @return string
     */
    private function _getTemplatePrefix()
    {
        $explodedControllerClass = explode('\\', $this->_skinModel->getWidgetConfigByKey($this->_cmsWidgetRecord->widget)->getControllerClassName());
        return lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr($explodedControllerClass[1], 0, -10));
    }

}