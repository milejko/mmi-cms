<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;


use Cms\Model\CategoryValidationModel;
use Cms\Model\SkinsetModel;
use Cms\Model\WidgetModel;
use Mmi\Mvc\Controller;

/**
 * Kontroler konfiguracji kategorii - stron CMS
 */
class CategoryWidgetRelationController extends Controller
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
        //walidacja czy można dodać kolejny taki widget
        if (!$this->id && !(new CategoryValidationModel($category, Registry::$config->skinset))->isWidgetAvailable($this->widget)) {
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $this->categoryId, 'originalId' => $this->originalId, 'uploaderId' => $category->id]);
        }
        //brak ID - nowy rekord
        if (!$this->id) {
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
        //wyszukiwanie relacji do edycji
        if ($this->id && null === $widgetRelationRecord = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->join('cms_category')->on('cms_category_id')
            ->whereCmsCategoryId()->equals($this->categoryId)
            ->whereWidget()->equals($this->widget)
            ->findPk($this->id)) {
            //brak relacji
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id]);
        }
        //modyfikacja breadcrumbów
        $this->view->adminNavigation()->removeLastBreadcrumb();
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.category.edit', $this->view->url(['module' => 'cmsAdmin', 'controller' => 'category', 'action' => 'edit', 'id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id], true));
        $this->view->adminNavigation()->appendBreadcrumb('menu.categoryWidgetRelation.config', '#');
        //model widgeta do widoku
        $this->view->widgetModel = new WidgetModel($widgetRelationRecord, Registry::$config->skinset);
        //rendering, lub przekierowanie jeśli kontroler zgłosił zapis
        $this->view->output = $this->view->categoryWidgetEdit($widgetRelationRecord);
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
        //brak skór
        if (!Registry::$config->skinset) {
            return;
        }
        //sekcje do widoku
        $this->view->sections = (new SkinsetModel(Registry::$config->skinset))->getSectionsByKey($category->template);
        //walidator możliwości dodawania widgetów
        $this->view->widgetValidator = new CategoryValidationModel($category, Registry::$config->skinset);
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
        //usuwanie - logika widgeta
        (new WidgetModel($widgetRelation, Registry::$config->skinset))->invokeDeleteAction($this->view);
        //usuwanie relacji
        $widgetRelation->delete();
        $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $this->categoryId, 'uploaderId' => $this->categoryId, 'originalId' => $this->originalId]);
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
     * @throws \Cms\Exception\CategoryWidgetException
     */
    public function sortAction()
    {
        $this->getResponse()->setTypePlain();
        //brak pola
        if (null === $serial = $this->getPost()->__get('widget-item')) {
            return $this->view->_('controller.categoryWidgetRelation.move.error');
        }
        //sortowanie
        (new \Cms\Model\CategoryWidgetModel($this->categoryId, Registry::$config->skinset))
            ->sortBySerial($serial);
        //pusty zwrot
        return '';
    }

}
