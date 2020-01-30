<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\Orm\CmsCategoryWidgetQuery;

/**
 * Kontroler atrybutów w widgetach
 */
class CategoryWidgetAttributeController extends Mvc\Controller
{

    /**
     * Edycja typu artykułu
     */
    public function editAction()
    {
        //wyszukiwanie widgeta
        if (null === ($categoryWidget = (new CmsCategoryWidgetQuery())->findPk($this->categoryWidgetId))) {
            $this->getMessenger()->addMessage('messenger.categoryWidgetAttribute.error', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryType', 'index');
        }
        $this->view->adminNavigation()->modifyBreadcrumb(3, 'menu.categoryWidget.container', $this->view->url(['action' => 'index', 'controller' => 'categoryWidget', 'id' => null, 'categoryWidgetId' => null]));
        $this->view->adminNavigation()->modifyBreadcrumb(4, 'menu.categoryWidget.edit', $this->view->url(['controller' => 'categoryWidget', 'id' => $this->categoryWidgetId, 'categoryWidgetId' => null]));
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.categoryWidgetAttribute.edit', '#');
        //rekord widgeta do widoku
        $this->view->categoryWidget = $categoryWidget;
        //rekord nowej relacji, lub edycja
        $relationRecord = new \Cms\Orm\CmsAttributeRelationRecord($this->id);
        $relationRecord->object = 'cmsCategoryWidget';
        $relationRecord->objectId = $this->categoryWidgetId;
        //formularz edycji atrybutów
        $relationForm = new Form\CategoryAttributeRelationForm($relationRecord);
        if ($relationForm->isSaved()) {
            $this->getMessenger()->addMessage('messenger.categoryWidgetAttribute.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryWidget', 'edit', ['id' => $this->categoryWidgetId]);
        }
        $this->view->relationForm = $relationForm;
    }

    /**
     * Usuwanie relacji atrybutu do szablonu
     */
    public function deleteAction()
    {
        $record = (new \Cms\Orm\CmsAttributeRelationQuery())->findPk($this->id);
        if ($record && $record->delete()) {
            $this->getMessenger()->addMessage('messenger.categoryWidgetAttribute.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'categoryWidget', 'edit', ['id' => $this->categoryWidgetId]);
    }

}