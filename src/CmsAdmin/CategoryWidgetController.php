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
        $form = new \CmsAdmin\Form\CategoryWidget(new \Cms\Orm\CmsCategoryWidgetRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('Zapisano konfigurację widgeta', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryWidget');
        }
        $this->view->widgetForm = $form;
        //brak identyfikatora
        if (!$this->id) {
            return;
        }
        //grid atrybutów
        $this->view->relationGrid = new \CmsAdmin\Plugin\CategoryAttributeRelationGrid(['object' => 'cmsCategoryWidget', 'objectId' => $this->id]);
        //rekord nowej, lub edytowanej relacji
        $relationRecord = new \Cms\Orm\CmsAttributeRelationRecord($this->relationId);
        $relationRecord->object = 'cmsCategoryWidget';
        $relationRecord->objectId = $this->id;
        //formularz edycji
        $relationForm = new Form\CategoryAttributeRelationForm($relationRecord);
        if ($relationForm->isSaved()) {
            $this->getMessenger()->addMessage('Wiązanie atrybutu zapisane poprawnie', true);
            $this->getResponse()->redirect('cmsAdmin', 'categoryWidget', 'edit', ['id' => $this->id]);
        }
        $this->view->relationForm = $relationForm;
    }

    /**
     * Usuwanie widgeta
     */
    public function deleteAction()
    {
        $server = (new \Cms\Orm\CmsCategoryWidgetQuery)->findPk($this->id);
        if ($server && $server->delete()) {
            $this->getMessenger()->addMessage('Usunięto konfigurację widgeta');
        }
        $this->getResponse()->redirect('cmsAdmin', 'categoryWidget');
    }

    /**
     * Usuwanie relacji widget atrybut
     */
    public function deleteAttributeRelationAction()
    {
        //usuwanie relacji
        (new AttributeController($this->getRequest()))->deleteAttributeRelationAction();
        $this->getResponse()->redirect('cmsAdmin', 'categoryWidget', 'edit', ['id' => $this->id]);
    }

}
