<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\App\CmsSkinsetConfig;
use Cms\Model\CategoryValidationModel;
use Cms\Model\SkinsetModel;
use Cms\Model\WidgetModel;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler konfiguracji kategorii - stron CMS
 */
class CategoryWidgetRelationController extends Controller
{

    /**
     * @Inject
     * @var CmsSkinsetConfig
     */
    private $cmsSkinsetConfig;

    /**
     * Edycja widgeta
     */
    public function editAction(Request $request)
    {
        //wyszukiwanie kategorii
        if ((null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->categoryId)) || $category->status != \Cms\Orm\CmsCategoryRecord::STATUS_DRAFT) {
            //brak kategorii
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //kategoria do widoku
        $this->view->category = $category;
        //walidacja czy można dodać kolejny taki widget
        if (!$this->id && !(new CategoryValidationModel($category, $this->cmsSkinsetConfig))->isWidgetAvailable($request->widget)) {
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $request->categoryId, 'originalId' => $request->originalId, 'uploaderId' => $category->id]);
        }
        //brak ID - nowy rekord
        if (!$this->id) {
            //nowy rekord relacji
            $widgetRelationRecord = new \Cms\Orm\CmsCategoryWidgetCategoryRecord();
            //parametry relacji
            $widgetRelationRecord->widget = $request->widget;
            $widgetRelationRecord->cmsCategoryId = $request->categoryId;
            //maksymalna wartość posortowania
            $maxOrder = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
                ->whereCmsCategoryId()->equals($request->categoryId)
                ->findMax('order');
            $widgetRelationRecord->order = $maxOrder !== null ? $maxOrder + 1 : 0;
        }
        //wyszukiwanie relacji do edycji
        if ($request->id && null === $widgetRelationRecord = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->join('cms_category')->on('cms_category_id')
            ->whereCmsCategoryId()->equals($request->categoryId)
            ->whereWidget()->equals($request->widget)
            ->findPk($request->id)) {
            //brak relacji
            $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id]);
        }
        //modyfikacja breadcrumbów
        $this->view->adminNavigation()->removeLastBreadcrumb()->removeLastBreadcrumb();
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.category.index', $this->view->url(['module' => 'cmsAdmin', 'controller' => 'category', 'action' => 'index', 'parentId' => $category->parentId]));
        $this->view->adminNavigation()->appendBreadcrumb('menu.category.edit', $this->view->url(['module' => 'cmsAdmin', 'controller' => 'category', 'action' => 'edit', 'id' => $category->id, 'originalId' => $category->cmsCategoryOriginalId, 'uploaderId' => $category->id], true));
        $this->view->adminNavigation()->appendBreadcrumb('menu.categoryWidgetRelation.config', '#');
        //model widgeta do widoku
        $this->view->widgetModel = new WidgetModel($widgetRelationRecord, $this->cmsSkinsetConfig);
        //rendering, lub przekierowanie jeśli kontroler zgłosił zapis
        $this->view->output = $this->view->categoryWidgetEdit($widgetRelationRecord);
    }

    /**
     * Lista podglądów widgetów
     */
    public function previewAction(Request $request)
    {
        //wyszukiwanie kategorii
        if (null === $category = (new \Cms\Orm\CmsCategoryQuery)->findPk($request->categoryId)) {
            $this->getResponse()->redirect('cmsAdmin', 'category', 'index');
        }
        //kategoria do widoku
        $this->view->category = $category;
        //brak skór
        if (!$this->cmsSkinsetConfig) {
            return;
        }
        //sekcje do widoku
        $this->view->sections = (new SkinsetModel($this->cmsSkinsetConfig))->getSectionsByKey($category->template);
        //walidator możliwości dodawania widgetów
        $this->view->widgetValidator = new CategoryValidationModel($category, $this->cmsSkinsetConfig);
    }

    /**
     * Kasowanie relacji
     */
    public function deleteAction(Request $request)
    {
        //wyszukiwanie relacji do edycji
        if (null === $widgetRelation = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($request->categoryId)
            ->findPk($this->id)) {
            return '';
        }
        //usuwanie - logika widgeta
        (new WidgetModel($widgetRelation, $this->cmsSkinsetConfig))->invokeDeleteAction();
        //usuwanie relacji
        $widgetRelation->delete();
        $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', ['id' => $request->categoryId, 'uploaderId' => $request->categoryId, 'originalId' => $request->originalId]);
    }

    /**
     * Zmiana widoczności relacji
     */
    public function toggleAction(Request $request)
    {
        //wyszukiwanie relacji do edycji
        if (null === $widgetRelation = (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)
            ->whereCmsCategoryId()->equals($request->categoryId)
            ->findPk($request->id)) {
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
    public function sortAction(Request $request)
    {
        $this->getResponse()->setTypePlain();
        //brak pola
        if (null === $serial = $request->getPost()->__get('widget-item')) {
            return $this->view->_('controller.categoryWidgetRelation.move.error');
        }
        //sortowanie
        (new \Cms\Model\CategoryWidgetModel($this->categoryId, $this->cmsSkinsetConfig))
            ->sortBySerial($serial);
        //pusty zwrot
        return '';
    }

}
