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
 * Kontroler sekcji szablonów
 */
class CategorySectionController extends Mvc\Controller
{

    /**
     * Edycja sekcji
     */
    public function editAction()
    {
        //wyszukiwanie szablonu
        if (null === ($categoryType = (new CmsCategoryTypeQuery())->findPk($this->categoryTypeId))) {
            $this->getMessenger()->addMessage('messenger.categorySection.error', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryType', 'index');
        }
        //zmiana breadcrumbów
        $this->view->adminNavigation()->modifyBreadcrumb(3, 'menu.categoryType.container', $this->view->url(['action' => 'index', 'controller' => 'categoryType', 'id' => null, 'categoryTypeId' => null]));
        $this->view->adminNavigation()->modifyBreadcrumb(4, 'menu.categoryType.edit', $this->view->url(['controller' => 'categoryType', 'id' => $this->categoryTypeId, 'categoryTypeId' => null]));
        $this->view->adminNavigation()->modifyLastBreadcrumb('menu.categorySection.edit', '#');
        //rekord szablonu do widoku
        $this->view->categoryType = $categoryType;
        //rekord nowej sekcji, lub edycja
        $sectionRecord = new \Cms\Orm\CmsCategorySectionRecord($this->id);
        $sectionRecord->categoryTypeId = $this->categoryTypeId;
        //formularz edycji sekcji
        $sectionForm = new Form\CategorySectionForm($sectionRecord);
        if ($sectionForm->isSaved()) {
            $this->getMessenger()->addMessage('messenger.categorySection.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryType', 'edit', ['id' => $this->categoryTypeId]);
        }
        $this->view->form = $sectionForm;
    }

    /**
     * Usuwanie sekcji
     */
    public function deleteAction()
    {
        $record = (new \Cms\Orm\CmsCategorySectionQuery())->findPk($this->id);
        if ($record && $record->delete()) {
            $this->getMessenger()->addMessage('messenger.categoryType.categoryType.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'categoryType', 'edit', ['id' => $this->categoryTypeId]);
    }

}
