<?php

namespace Cms\Model;

use Cms\App\CmsSkinsetConfig;
use Cms\Orm\CmsCategoryRecord;
use Mmi\App\KernelException;
use Mmi\Mvc\View;
use Cms\App\CmsTemplateConfig;
use Cms\TemplateController;
use CmsAdmin\Form\CategoryForm;
use Mmi\App\App;

/**
 * Model szablonu
 */
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
     * @param CmsSkinsetConfig $skinsetConfig
     */
    public function __construct(CmsCategoryRecord $categoryRecord, CmsSkinsetConfig $skinsetConfig)
    {
        $this->_categoryRecord = $categoryRecord;
        //brak zdefiniowanego template w szablonie
        if (null === $this->_categoryRecord->template) {
            return;
        }
        //szablon nieodnaleziony
        if (null === $this->_templateConfig = (new SkinsetModel($skinsetConfig))->getTemplateConfigByKey($categoryRecord->template)) {
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
     * Render akcji wyświetlenia
     * @param View $view
     * @return string
     */
    public function renderDisplayAction(View $view)
    {
        //wywołanie akcji wyświetlenia
        if (null === $controller = $this->_createController()) {
            return;
        }
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
        //wywołanie akcji usuwania
        if (null === $controller = $this->_createController()) {
            return;
        }
        $controller->deleteAction($view->request);
    }

    /**
     * Dekorowanie formularza edycji kategorii
     * @param View $view
     * @param CategoryForm $categoryForm
     * @return void
     */
    public function invokeDecorateEditForm(CategoryForm $categoryForm)
    {
        //wywołanie akcji dekoracji
        if (null === $controller = $this->_createController()) {
            return;
        }
        $controller->decorateEditForm($categoryForm);
    }

    /**
     * Wywołanie akcji po zapisie
     * @param View $view
     * @param CategoryForm $categoryForm
     * @return void
     */
    public function invokeBeforeSaveEditForm(CategoryForm $categoryForm)
    {
        //wywołanie akcji po zapisie
        if (null === $controller = $this->_createController()) {
            return;
        }
        $controller->beforeSaveEditForm($categoryForm);
    }

    /**
     * Wywołanie akcji po zapisie
     * @param View $view
     * @param CategoryForm $categoryForm
     * @return void
     */
    public function invokeAfterSaveEditForm(CategoryForm $categoryForm)
    {
        //wywołanie akcji po zapisie
        if (null === $controller = $this->_createController()) {
            return;
        }
        $controller->afterSaveEditForm($categoryForm);
    }

    /**
     * Tworzy instancję kontrolera
     * @return TemplateController
     */
    private function _createController()
    {
        //getting the controller name
        $controllerName = $this->_templateConfig->getControllerClassName();
        //missing controller
        if (!App::$di->has($this->_templateConfig->getControllerClassName())) {
            throw new KernelException('Missing controller: ' . $controllerName);
        }
        //getting controller from the DI
        $targetController = App::$di->get($controllerName);
        //controller invalid
        if (!($targetController instanceof TemplateController)) {
            throw new KernelException($controllerName . ' should extend \Cms\TemplateController');
        }
        //injecting category record
        $targetController->setCategoryRecord($this->_categoryRecord);
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