<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

class ConnectorController extends Mvc\Controller
{

    public function indexAction()
    {
        $form = new Form\ConnectorImportContentForm;
        if ($form->isSaved() && $form->getElement('file')) {
            $this->getMessenger()->addMessage('Treść zaimportowana poprawnie', true);
            $this->getResponse()->redirect('cmsAdmin', 'connector', 'files');
        }
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('Import zakończony', true);
            $this->getResponse()->redirect('cmsAdmin', 'index', 'index');
        }
        $this->view->form = $form;
    }

    public function filesAction()
    {
        $form = new Form\ConnectorImportFilesForm;
        if ($form->isSaved()) {
            $this->view->files = $form->getOption('data');
        } else {
            $this->view->form = $form;
        }
    }

}
