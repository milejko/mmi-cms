<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\Orm\CmsCategorySectionQuery;
use Cms\Orm\CmsCategoryWidgetQuery;
use Mmi\Filter\EmptyToNull;

/**
 * Kontroler konfiguracji kategorii - stron CMS
 */
class CategoryWidgetRelationController extends Mvc\Controller
{

    /**
     * Konfiguracja widgeta
     */
    public function configAction()
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
    }

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
        //dostępne widgety
        $this->view->availableWidgets = (new CmsCategoryWidgetQuery())->find();
        //sekcje do widoku
        $this->view->sections = (new CmsCategorySectionQuery)
            ->whereCategoryTypeId()->equals($category->cmsCategoryTypeId)
            ->find();
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
