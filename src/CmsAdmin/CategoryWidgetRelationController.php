<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use App\Registry;
use Cms\Model\SkinModel;
use Cms\WidgetController;

/**
 * Kontroler konfiguracji kategorii - stron CMS
 */
class CategoryWidgetRelationController extends Mvc\Controller
{

    /**
     * Edycja widgeta
     */
    public function editAction()
    {
        //wyszukiwanie kategorii
        if ((null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->categoryId)) || $category->status != \Cms\Orm\CmsCategoryRecord::STATUS_DRAFT) {
            //brak kategorii
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //kategoria do widoku
        $this->view->category = $category;
        //iteracja po dostępnych skórach
        foreach (Registry::$config->skinset->getSkins() as $skin) {
            $skinModel = new SkinModel($skin);
            //w skórze nie ma tego szablonu
            if (!$skinModel->templateExists($category->template)) {
                continue;
            }
            //wyszukiwanie widgeta
            $widget = $skinModel->getWidgetByKey($this->widget);
        }
        //widget niekompatybilny
        if (!isset($widget)) {
            //przekierowanie na stronę edycji
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
                'id' => $this->categoryId,
                'originalId' => $this->originalId,
                'uploaderId' => $this->uploaderId,
            ]);
        }
        //widget do widoku
        $this->view->widget = $widget;
        //wyszukiwanie relacji do edycji
        if (null === $widgetRelationRecord = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->categoryId)
            ->whereWidget()->equals($this->widget)
            ->findPk($this->id)) {
            //nowy rekord relacji
            $widgetRelationRecord = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
            //parametry relacji
            $widgetRelationRecord->widget = $this->widget;
            $widgetRelationRecord->cmsCategoryId = $this->categoryId;
            //maksymalna wartość posortowania
            $maxOrder = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
                ->whereCmsCategoryId()->equals($this->categoryId)
                ->findMax('order');
            $widgetRelationRecord->order = $maxOrder !== null ? $maxOrder + 1 : 0;
        }
        //modyfikacja breadcrumbów
        $this->view->adminNavigation()->modifyBreadcrumb(4, 'menu.category.edit', $this->view->url(['controller' => 'category', 'action' => 'edit', 'id' => $this->categoryId, 'categoryId' => null, 'widgetId' => null]));
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.categoryWidgetRelation.config', '#');
        //odczytywanie nazwy kontrolera
        $controllerClass = $widget->getControllerClassName();
        //powołanie kontrolera z rekordem relacji
        $targetController = new $controllerClass($this->getRequest(), $this->view, $widgetRelationRecord);
        //kontroler nie jest poprawny
        if (!($targetController instanceof WidgetController)) {
            //przekierowanie na stronę edycji
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
                'id' => $this->categoryId,
                'originalId' => $this->originalId,
                'uploaderId' => $this->uploaderId,
            ]);
        }
        //wywołanie akcji
        $targetController->editAction();
        $explodedControllerClass = explode('\\', $widget->getControllerClassName());
        //render szablonu
        $this->view->output = $this->view->renderTemplate(lcfirst($explodedControllerClass[0]) . '/' . lcfirst(substr($explodedControllerClass[1], 0, -10)) . '/edit');
        //kontroler zaraportował zapis
        if ($targetController->isSaved()) {
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
                'id' => $this->categoryId,
                'originalId' => $this->originalId,
                'uploaderId' => $this->originalUploaderId,
            ]);
        }
    }

    /**
     * Konfiguracja widgeta
     */
    /*public function configAction()
    {
        //wyszukiwanie kategorii
        if ((null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->categoryId)) || $category->status != \Cms\Orm\CmsCategoryRecord::STATUS_DRAFT) {
            //brak kategorii
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //wyszukiwanie sekcji (opcjonalnej)
        if ($this->sectionId && null === $section = (new CmsCategorySectionQuery())->findPk($this->sectionId)) {
            //brak sekcji
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
                'id' => $this->categoryId,
                'originalId' => $this->originalId,
                'uploaderId' => $this->uploaderId,
            ]);
        }
        //kategoria do widoku
        $this->view->category = $category;
        //wyszukiwanie widgeta
        if (null === $widgetRecord = (new \Cms\Orm\CmsCategoryWidgetQuery)->findPk($this->widgetId)) {
            //brak widgeta
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
                'id' => $this->categoryId,
                'originalId' => $this->originalId,
                'uploaderId' => $this->uploaderId,
            ]);
        }
        //wyszukiwanie relacji do edycji
        if (null === $widgetRelationRecord = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->categoryId)
            ->andFieldCmsCategoryWidgetId()->equals($widgetRecord->id)
            ->findPk($this->id)) {
            //nowy rekord relacji
            $widgetRelationRecord = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
            //parametry relacji
            $widgetRelationRecord->cmsCategoryWidgetId = $widgetRecord->id;
            $widgetRelationRecord->cmsCategoryId = $this->categoryId;
            //maksymalna wartość posortowania
            $maxOrder = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
                ->whereCmsCategoryId()->equals($this->categoryId)
                ->findMax('order');
            $widgetRelationRecord->order = $maxOrder !== null ? $maxOrder + 1 : 0;
        }
        //rekord widgeta do widoku
        $this->view->widgetRecord = $widgetRecord;
        //domyślna klasa formularza
        $widgetRecord->formClass = $widgetRecord->formClass ? : '\CmsAdmin\Form\CategoryAttributeWidgetForm';
        //modyfikacja breadcrumbów
        $this->view->adminNavigation()->modifyBreadcrumb(4, 'menu.category.edit', $this->view->url(['controller' => 'category', 'action' => 'edit', 'id' => $this->categoryId, 'categoryId' => null, 'widgetId' => null]));
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.categoryWidgetRelation.config', '#');
        //zapis sekcji (opcjonalnej)
        $widgetRelationRecord->cmsCategorySectionId = (new EmptyToNull())->filter($this->sectionId);
        //instancja formularza
        $form = new $widgetRecord->formClass($widgetRelationRecord, ['widgetId' => $widgetRecord->id]);
        //wartości z zapisanej konfiguracji
        $formValues = $widgetRelationRecord->getConfig()->toArray();
        //nadpisanie wartościami z POSTA, jeśli zosały przesłane
        if (!$this->getPost()->isEmpty() && is_array($this->getPost()->__get($form->getBaseName()))) {
            $formValues = array_merge($formValues, $this->getPost()->__get($form->getBaseName()));
        }
        $form->setFromArray($formValues);
        //form zapisany
        if ($form->isSaved()) {
            //zapis konfiguracji
            $widgetRelationRecord->setConfigFromArray($widgetRelationRecord->getOptions());
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
                'id' => $this->categoryId,
                'originalId' => $this->originalId,
                'uploaderId' => $this->originalUploaderId,
            ]);
        }
        //form do widoku
        $this->view->widgetRelationForm = $form;
    }*/

    /**
     * Lista podglądów widgetów
     */
    public function previewAction()
    {
        //wyszukiwanie kategorii
        if (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($this->categoryId)) {
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //kategoria do widoku
        $this->view->category = $category;
        //brak skór
        if (!Registry::$config->skinset) {
            return;
        }
        //dostępne sekcje
        $this->view->sections = [];
        //iteracja po skórach
        foreach (Registry::$config->skinset->getSkins() as $skin) {
            $skinModel = new SkinModel($skin);
            if (!$skinModel->templateExists($category->template)) {
                continue;
            }
            //pobranie sekcji dla szablonu kategorii
            $this->view->sections = (new SkinModel($skin))->getSectionsByTemplateKey($category->template);
            break;
        }
    }

    /**
     * Kasowanie relacji
     */
    public function deleteAction()
    {
        //wyszukiwanie relacji do edycji
        if (null === $widgetRelation = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->categoryId)
            ->findPk($this->id)) {
            return '';
        }
        //usuwanie relacji
        $widgetRelation->delete();
        return '';
    }

    /**
     * Zmiana widoczności relacji
     */
    public function toggleAction()
    {
        //wyszukiwanie relacji do edycji
        if (null === $widgetRelation = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($this->categoryId)
            ->findPk($this->id)) {
            return '';
        }
        //zmiana widoczności relacji
        $widgetRelation->toggle();
        return '';
    }

    /**
     * Sortowanie ajax widgetów
     * @return string
     */
    public function sortAction()
    {
        $this->getResponse()->setTypePlain();
        //brak pola
        if (null === $serial = $this->getPost()->__get('widget-item')) {
            return $this->view->_('controller.categoryWidgetRelation.move.error');
        }
        //sortowanie
        (new \Cms\Model\CategoryWidgetModel($this->categoryId))
            ->sortBySerial($serial);
        //pusty zwrot
        return '';
    }

}
