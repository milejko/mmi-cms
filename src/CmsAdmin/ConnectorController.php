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
 * Kontroler importu danych z CMS
 */
class ConnectorController extends Mvc\Controller
{

    /**
     * Krok pierwszy - import treści
     */
    public function indexAction()
    {
        //formularz
        $form = new Form\ConnectorImportContentForm;
        //formularz zapisany + przejście do importu plików
        if ($form->isSaved() && $form->getElement('file')) {
            $this->getMessenger()->addMessage('Treść zaimportowana poprawnie', true);
            $this->getResponse()->redirect('cmsAdmin', 'connector', 'files');
        }
        //form zapisany bez importu plików
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('Import zakończony', true);
            $this->getResponse()->redirect('cmsAdmin', 'index', 'index');
        }
        //form do widoku
        $this->view->form = $form;
    }

    /**
     * Krok drugi - import plików
     */
    public function filesAction()
    {
        //formularz
        $form = new Form\ConnectorImportFilesForm;
        //formularz zapisany
        if ($form->isSaved()) {
            //przekazanie listy plików do downloadu
            $this->view->files = $form->getOption('data');
            //otwarcie sesji
            $session = new \Mmi\Session\SessionSpace(\Cms\Model\ConnectorModel::SESSION_SPACE);
            $this->view->downloadUrl = base64_encode($session->url);
        } else {
            //form do widoku
            $this->view->form = $form;
        }
    }

}
