<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Mvc\Controller;

/**
 * Kontroler kontaktów
 */
class ContactController extends Controller
{

    /**
     * Lista zgłoszeń
     */
    public function indexAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\ContactGrid;
    }

    /**
     * Lista tematów zgłoszeń
     */
    public function subjectAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\ContactOptionGrid;
    }

    /**
     * Edycja tematu
     */
    public function editSubjectAction()
    {
        $form = new \CmsAdmin\Form\Contact\Option(new \Cms\Orm\CmsContactOptionRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.contact.subject.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'contact', 'subject');
        }
        $this->view->optionForm = $form;
    }

    /**
     * Usuwanie tematu
     */
    public function deleteSubjectAction()
    {
        $option = (new \Cms\Orm\CmsContactOptionQuery)->findPk($this->id);
        if ($option && $option->delete()) {
            $this->getMessenger()->addMessage('messenger.contact.subject.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'contact', 'subject');
    }

    /**
     * Usuwanie zgłoszenia
     */
    public function deleteAction()
    {
        $contact = (new \Cms\Orm\CmsContactQuery)->findPk($this->id);
        if ($contact && $contact->delete()) {
            $this->getMessenger()->addMessage('messenger.contact.message.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'contact');
    }

    /**
     * Edycja/odpowiedź na zgłoszenie
     */
    public function editAction()
    {
        $form = new \CmsAdmin\Form\Contact(new \Cms\Orm\CmsContactRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.contact.message.sent', true);
            $this->getResponse()->redirect('cmsAdmin', 'contact');
        }
        $this->view->contactForm = $form;
    }

}
