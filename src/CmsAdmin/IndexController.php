<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Cms\App\CmsScopeConfig;
use Cms\App\CmsSkinsetConfig;
use CmsAdmin\Form\ScopeSelectForm;
use Mmi\Security\AuthInterface;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Session\SessionInterface;

/**
 * Kontroler główny panelu administracyjnego
 */
class IndexController extends Controller
{

    /**
     * @Inject
     */
    private AuthInterface $auth;

    /**
     * @Inject
     */
    private SessionInterface $session;

    /**
     * @Inject
     */
    private CmsScopeConfig $scopeConfig;

    /**
     * @Inject
     */
    private CmsSkinsetConfig $skinsetConfig;

    /**
     * Strona główna admina
     */
    public function indexAction()
    {
        $this->view->user = (new \Cms\Orm\CmsAuthQuery)->findPk($this->auth->getId());
    }

    /**
     * Logowanie
     */
    public function loginAction(Request $request)
    {
        //jesli zalogowany
        if ($this->auth->hasIdentity()) {
            return $this->getResponse()->redirect('cmsAdmin');
        }
        //formularz logowania
        $form = new \CmsAdmin\Form\Login;
        $this->view->loginForm = $form;
        //brak wysłanych danych
        if (!$form->isMine()) {
            return;
        }
        //logowanie niepoprawne
        if (!$form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.index.login.fail', false);
            return;
        }
        //regeneracja ID sesji
        $this->session->regenerateId();
        //zalogowano
        $this->getMessenger()->addMessage('messenger.index.login.success', true);
        $referer = $request->getReferer();
        //przekierowanie na referer
        if ($referer && $referer != $this->getRequest()->getServer()->requestUri) {
            return $this->getResponse()->redirectToUrl($referer);
        }
        $this->getResponse()->redirect('cmsAdmin');
    }

    /**
     * Akcja wylogowania
     */
    public function logoutAction()
    {
        $this->auth->clearIdentity();
        $this->getMessenger()->addMessage('messenger.index.logout.success', true);
        $this->getResponse()->redirect('cmsAdmin');
    }

    /**
     * Zmiana hasła
     */
    public function passwordAction()
    {
        $form = new \CmsAdmin\Form\Password;
        $this->view->passwordForm = $form;
        //brak wysłanych danych
        if (!$form->isMine()) {
            return;
        }
        //hasło niepoprawne (nowe lub stare)
        if (!$form->isSaved()) {
            return;
        }
        $this->getMessenger()->addMessage('messenger.index.password.success', true);
        //wylogowanie
        $this->auth->clearIdentity();
        $this->getResponse()->redirect('cmsAdmin');
    }

    public function scopeMenuAction()
    {
        $options = [];
        foreach ($this->skinsetConfig->getSkins() as $skin) {
            $options[$skin->getKey()] = $skin->getName();
        }
        if (!$this->scopeConfig->getName()) {
            $this->scopeConfig->setName(array_key_first($options));
        }
        $form = new ScopeSelectForm(null, [ScopeSelectForm::OPTION_SELECTED => $this->scopeConfig->getName(), ScopeSelectForm::OPTION_MULTIOPTIONS => $options]);

        $this->view->form = $form;
        //obsługa POST
        if ($form->isMine()) {
            $this->scopeConfig->setName($form->getElement('scope')->getValue());
            $this->getMessenger()->addMessage('messenger.index.scopeMenu.success', true);
            $this->getResponse()->redirect('cmsAdmin');
        }
    }

}
