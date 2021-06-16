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

    private const MESSENGER_LOGIN_FAILED = 'messenger.index.login.fail';
    private const MESSENGER_LOGIN_SUCCESS = 'messenger.index.login.success';
    private const MESSENGER_LOGOUT_SUCCESS = 'messenger.index.logout.success';
    private const MESSENGER_PASSWORD_SUCCESS = 'messenger.index.password.success';
    private const MESSENGER_SCOPE_SUCCESS = 'messenger.index.scopeMenu.success';

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
     * @Inject
     */
    private Request $masterRequest;

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
            $this->getMessenger()->addMessage(self::MESSENGER_LOGIN_FAILED, false);
            return;
        }
        //regeneracja ID sesji
        $this->session->regenerateId();
        //zalogowano
        $this->getMessenger()->addMessage(self::MESSENGER_LOGIN_SUCCESS, true);
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
        $this->getMessenger()->addMessage(self::MESSENGER_LOGOUT_SUCCESS, true);
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
        $this->getMessenger()->addMessage(self::MESSENGER_PASSWORD_SUCCESS, true);
        //wylogowanie
        $this->auth->clearIdentity();
        $this->getResponse()->redirect('cmsAdmin');
    }

    public function scopeMenuAction()
    {
        $options = [];
        //opcje
        foreach ($this->skinsetConfig->getSkins() as $skin) {
            $options[$skin->getKey()] = $skin->getName();
        }
        //brak domen
        if (count($options) < 2) {
            return;
        }
        $form = new ScopeSelectForm(null, [ScopeSelectForm::OPTION_SELECTED => $this->scopeConfig->getName(), ScopeSelectForm::OPTION_MULTIOPTIONS => $options]);
        //form do widoku
        $this->view->form = $form;
        //obsługa POST
        if ($form->isMine()) {
            $this->scopeConfig->setName($form->getElement('scope')->getValue());
            $this->getMessenger()->addMessage(self::MESSENGER_SCOPE_SUCCESS, true);
            //przekierowanie na referer
            if ($this->masterRequest->getReferer()) {
                return $this->getResponse()->redirectToUrl($this->masterRequest->getReferer());
            }
            return $this->getResponse()->redirect('cmsAdmin');
        }
    }

}
