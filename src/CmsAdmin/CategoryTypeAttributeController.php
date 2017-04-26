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
 * Typy Artykuły
 */
class CategoryTypeAttributeController extends Mvc\Controller
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
        $form = new \CmsAdmin\Form\CategoryType(new \Cms\Orm\CmsCategoryTypeRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('Typ artykułu zapisany poprawnie', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryType');
        }
        $this->view->categoryTypeForm = $form;
    }

    /**
     * Usuwanie typu artykułu
     */
    public function deleteAction()
    {
        $record = (new \Cms\Orm\CmsCategoryTypeQuery)->findPk($this->id);
        if ($record && $record->delete()) {
            $this->getMessenger()->addMessage('Poprawnie usunięto typ artykułu', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'categoryType');
    }

}
