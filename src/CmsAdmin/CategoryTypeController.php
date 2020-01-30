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
 * Kontroler szablonów (typów artykułów)
 */
class CategoryTypeController extends Mvc\Controller
{

    /**
     * Lista typów artykułów
     */
    public function indexAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\CategoryTypeGrid;
    }

    /**
     * Edycja typu artykułu
     */
    public function editAction()
    {
        //zmiana breadcrumbów
        $this->view->adminNavigation()->modifyBreadcrumb(3, 'menu.categoryType.container', $this->view->url(['action' => 'index', 'id' => null]));
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.categoryType.edit', '#');
        $form = new \CmsAdmin\Form\CategoryType(new \Cms\Orm\CmsCategoryTypeRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.categoryType.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryType');
        }
        $this->view->categoryTypeForm = $form;
        //grid atrybutów
        $this->view->relationGrid = new \CmsAdmin\Plugin\CategoryAttributeRelationGrid([
            'object' => 'cmsCategoryType',
            'objectId' => $this->id,
            'requestParams' => [
                'controller' => 'categoryTypeAttribute',
                'categoryTypeId' => $this->id,
            ]
        ]);
        //grid sekcji
        $this->view->sectionGrid = new \CmsAdmin\Plugin\CategorySectionGrid(['typeId' => $this->id]);
    }

    /**
     * Usuwanie szablonu
     */
    public function deleteAction()
    {
        $record = (new \Cms\Orm\CmsCategoryTypeQuery)->findPk($this->id);
        if ($record && $record->delete()) {
            $this->getMessenger()->addMessage('messenger.categoryType.categoryType.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'categoryType');
    }

}
