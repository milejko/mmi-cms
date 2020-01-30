<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\Orm\CmsCategoryTypeQuery;

/**
 * Typy atrybuty w 
 */
class CategoryTypeAttributeController extends Mvc\Controller
{

    /**
     * Edycja wiązania
     */
    public function editAction()
    {
        //wyszukiwanie widgeta
        if (null === ($categoryType = (new CmsCategoryTypeQuery())->findPk($this->categoryTypeId))) {
            $this->getMessenger()->addMessage('messenger.categoryTypeAttribute.error', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryType', 'index');
        }
        $this->view->adminNavigation()->modifyBreadcrumb(3, 'menu.categoryType.container', $this->view->url(['action' => 'index', 'controller' => 'categoryType', 'id' => null, 'categoryTypeId' => null]));
        $this->view->adminNavigation()->modifyBreadcrumb(4, 'menu.categoryType.edit', $this->view->url(['controller' => 'categoryType', 'id' => $this->categoryTypeId, 'categoryTypeId' => null]));
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.categoryTypeAttribute.edit', '#');
        //rekord Typea do widoku
        $this->view->categoryType = $categoryType;
        //rekord nowej relacji, lub edycja
        $relationRecord = new \Cms\Orm\CmsAttributeRelationRecord($this->id);
        $relationRecord->object = 'cmsCategoryType';
        $relationRecord->objectId = $this->categoryTypeId;
        //formularz edycji atrybutów
        $relationForm = new Form\CategoryAttributeRelationForm($relationRecord);
        if ($relationForm->isSaved()) {
            $this->getMessenger()->addMessage('messenger.categoryTypeAttribute.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryType', 'edit', ['id' => $this->categoryTypeId]);
        }
        $this->view->form = $relationForm;
    }

    /**
     * Usuwanie relacji atrybutu do szablonu
     */
    public function deleteAction()
    {
        $record = (new \Cms\Orm\CmsAttributeRelationQuery())->findPk($this->id);
        if ($record && $record->delete()) {
            $this->getMessenger()->addMessage('messenger.categoryTypeAttribute.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'categoryType', 'edit', ['id' => $this->categoryTypeId]);
    }

}
