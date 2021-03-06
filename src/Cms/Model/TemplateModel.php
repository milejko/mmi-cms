<?php

namespace Cms\Model;

use Cms\Api\ErrorTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsSkinsetConfig;
use Cms\Orm\CmsCategoryRecord;
use Mmi\App\KernelException;
use Mmi\Mvc\View;
use Mmi\Http\Request;
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
     * Konfiguracja skóry
     * @var CmsSkinsetConfig
     */
    private $_skinsetConfig;

    /**
     * Konstruktor
     * @param CmsCategoryRecord $cmsCategoryRecord
     * @param CmsSkinsetConfig $skinsetConfig
     */
    public function __construct(CmsCategoryRecord $categoryRecord, CmsSkinsetConfig $skinsetConfig)
    {
        $this->_categoryRecord = $categoryRecord;
        $this->_skinsetConfig = $skinsetConfig;
        //brak zdefiniowanego template w szablonie
        if (null === $this->_categoryRecord->template) {
            return;
        }
        //konfiguracja szablonu
        $this->_templateConfig = (new SkinsetModel($skinsetConfig))->getTemplateConfigByKey($categoryRecord->template);
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
     * Pobiera obiekt transportowy
     */
    public function getTransportObject(Request $request): TransportInterface
    {
        //pobranie obiektu transportowego szablonu
        if (null === $controller = $this->_createController()) {
            return (new ErrorTransport())->setMessage('Controller not found');
        }
        return $controller->getTransportObject($request);
    }

    /**
     * Wywołanie akcji usuwania
     * @param View $view
     * @return void
     */
    public function invokeDeleteAction()
    {
        //wywołanie akcji usuwania
        if (null === $controller = $this->_createController()) {
            return;
        }
        $controller->deleteAction();
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
        if (null === $this->_templateConfig) {
            return;
        }
        //getting the controller name
        $controllerName = $this->_templateConfig->getControllerClassName();
        //missing controller
        if (!App::$di->has($controllerName)) {
            throw new KernelException('Missing controller: ' . $controllerName);
        }
        //getting controller from the DI
        $targetController = App::$di->get($controllerName);
        //controller invalid
        if (!($targetController instanceof TemplateController)) {
            throw new KernelException($controllerName . ' should extend \Cms\TemplateController');
        }
        //injecting category record & skinset config
        $targetController->setCategoryRecord($this->_categoryRecord);
        $targetController->setSkinsetConfig($this->_skinsetConfig);
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
        return lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr(array_pop($explodedControllerClass), 0, -10));
    }

}
