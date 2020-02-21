<?php

namespace Cms\Model;

use App\Registry;
use Cms\Exception\CategoryWidgetException;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\WidgetController;
use Mmi\App\KernelException;
use Mmi\Mvc\View;
use Cms\App\CmsWidgetConfig;

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

    /**
     * Konstruktor
     * @param CmsCategoryWidgetCategoryRecord $cmsWidgetRecord
     */
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
            if (null === $skinModel->getTemplateByKey($template)) {
                continue;
            }
            //wyszukiwanie widgeta
            $this->_widgetConfig = $skinModel->getWidgetByKey($cmsWidgetRecord->widget);
        }
        if (!isset($this->_widgetConfig)) {
            throw new KernelException('Compatible widget not found: ' . $cmsWidgetRecord->widget);
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
        $controllerClass = $this->_widgetConfig->getControllerClassName();
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
        $explodedControllerClass = explode('\\', $this->_widgetConfig->getControllerClassName());
        return lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr($explodedControllerClass[1], 0, -10));
    }

}