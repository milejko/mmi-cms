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
 * Kontroler użytkowników
 */
class AuthController extends Mvc\Controller
{

    /**
     * Lista użytkowników
     */
    public function indexAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\AuthGrid;
    }

    /**
     * Edycja użytkownika
     */
    public function editAction()
    {
        if (\App\Registry::$config->ldap) {
            $this->view->ldap = \App\Registry::$config->ldap->active;
        }

        $form = new \CmsAdmin\Form\Auth(new \Cms\Orm\CmsAuthRecord($this->id));
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.auth.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'auth');
        }
        $this->view->authForm = $form;
    }

    /**
     * Kasowanie użytkownika
     */
    public function deleteAction()
    {
        $auth = (new \Cms\Orm\CmsAuthQuery)->findPk($this->id);
        if ($auth && $auth->delete()) {
            $this->getMessenger()->addMessage('messenger.auth.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'auth');
    }

    /**
     * Akcja jsonowa wyszukująca użytkowników w LDAP
     */
    public function autocompleteAction()
    {
        //typ odpowiedzi
        $this->getResponse()->setTypeJson();
        //za krótki ciąg
        if (strlen(trim($this->term)) < 3) {
            return json_encode([]);
        }
        //zwraca odpowiedz JSON
        return json_encode((new \Cms\Model\Auth)->ldapAutocomplete($this->term . '*'));
    }

}
