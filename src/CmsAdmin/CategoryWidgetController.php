<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler widgetów kategorii
 */
class CategoryWidgetController extends Mvc\Controller
{

    /**
     * Lista widgetów
     */
    public function indexAction()
    {
        $grid = new \CmsAdmin\Plugin\CategoryWidgetGrid;
        $this->view->grid = $grid;
    }

    /**
     * Edycja widgeta
     */
    public function editAction()
    {
        //zmiana breadcrumbów
        $this->view->adminNavigation()->modifyBreadcrumb(3, 'menu.categoryWidget.container', $this->view->url(['action' => 'index', 'id' => null]));
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.categoryWidget.edit', '#');        
        $form = new \CmsAdmin\Form\CategoryWidget(new \Cms\Orm\CmsCategoryWidgetRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.categoryWidget.widgetConfig.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryWidget');
        }
        $this->view->widgetForm = $form;
        //grid atrybutów
        $this->view->relationGrid = new \CmsAdmin\Plugin\CategoryAttributeRelationGrid([
            'object' => 'cmsCategoryWidget', 
            'objectId' => $this->id,
            'requestParams' => [
                'controller' => 'categoryWidgetAttribute',
                'categoryWidgetId' => $this->id,
            ]
        ]);
    }

    /**
     * Usuwanie widgeta
     */
    public function deleteAction()
    {
        $server = (new \Cms\Orm\CmsCategoryWidgetQuery)->findPk($this->id);
        if ($server && $server->delete()) {
            $this->getMessenger()->addMessage('messenger.categoryWidget.widgetConfig.deleted');
        }
        $this->getResponse()->redirect('cmsAdmin', 'categoryWidget');
    }

}
