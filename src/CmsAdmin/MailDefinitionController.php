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
 * Definicje szablonów maili
 */
class MailDefinitionController extends Mvc\Controller
{

    /**
     * Lista szablonów
     */
    public function indexAction()
    {
        $grid = new \CmsAdmin\Plugin\MailDefinitionGrid;
        $this->view->grid = $grid;
    }

    /**
     * Edycja szablonu
     */
    public function editAction()
    {
        $form = new \CmsAdmin\Form\Mail\Definition(new \Cms\Orm\CmsMailDefinitionRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.mailDefinition.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'mailDefinition');
        }
        $this->view->definitionForm = $form;
    }

    /**
     * Usuwanie szablonu
     */
    public function deleteAction()
    {
        $definition = (new \Cms\Orm\CmsMailDefinitionQuery)->findPk($this->id);
        try {
            if ($definition && $definition->delete()) {
                $this->getMessenger()->addMessage('messenger.mailDefinition.deleted');
            }
        } catch (\Mmi\Db\Exception $e) {
            $this->getMessenger()->addMessage('messenger.mailDefinition.delete.error', false);
        }
        $this->getResponse()->redirect('cmsAdmin', 'mailDefinition');
    }

}
