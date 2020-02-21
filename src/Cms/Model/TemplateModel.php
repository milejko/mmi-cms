<?php

namespace Cms\Model;

use App\Registry;
use Cms\Orm\CmsCategoryRecord;
use Cms\WidgetController;
use Mmi\App\KernelException;
use Mmi\Mvc\View;
use Cms\App\CmsTemplateConfig;
use Cms\TemplateController;

class TemplateModel
{
    /**
     * Rekord kategorii
     * @var CmsCategoryRecord
     */
    private $_categoryRecord;

    /**
     * Konfiguracja widgetas
     * @var CmsTemplateConfig
     */
    private $_templateConfig;

    /**
     * Konstruktor
     * @param CmsCategoryRecord $cmsCategoryRecord
     */
    public function __construct(CmsCategoryRecord $categoryRecord)
    {
        $this->_categoryRecord = $categoryRecord;
        //brak zdefiniowanego szablonu
        if (!$categoryRecord->template) {
            throw new KernelException('Category template not specified');
        }
        //iteracja po dostępnych skórach
        foreach (Registry::$config->skinset->getSkins() as $skin) {
            $skinModel = new SkinModel($skin);
            //w skórze nie ma tego szablonu
            if (null !== ($this->_templateConfig = $skinModel->getTemplateByKey($categoryRecord->template))) {
                break;
            }
        }
        if (!isset($this->_templateConfig)) {
            throw new KernelException('Template not found');
        }
    }

    /**
     * Pobranie konfiguracji szablonu
     * @return CmsTemplateConfig
     */
    public function getTemplateConfg()
    {
        return $this->_templateConfig;
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
     * Wywołanie akcji usuwania
     * @param View $view
     * @return void
     */
    public function deleteAction(View $view)
    {
        //wywołanie akcji usuwania
        $controller = $this->_createController($view);
        $controller->deleteAction();
    }

    /**
     * Tworzy instancję kontrolera
     * @return WidgetController
     */
    private function _createController(View $view)
    {
        //odczytywanie nazwy kontrolera
        $controllerClass = $this->_templateConfig->getControllerClassName();
        //powołanie kontrolera z rekordem relacji
        $targetController = new $controllerClass($view->request, $view, $this->_categoryRecord);
        //kontroler nie jest poprawny
        if (!($targetController instanceof TemplateController)) {
            throw new KernelException('Not an instance of WidgetController');
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
        $explodedControllerClass = explode('\\', $this->_templateConfig->getControllerClassName());
        return lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr($explodedControllerClass[1], 0, -10));
    }

}