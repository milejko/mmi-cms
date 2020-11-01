<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Http\HttpServerEnv;
use Mmi\Security\Auth;
use Mmi\Http\Request;
use Mmi\Session\Session;

/**
 * Kontroler główny panelu administracyjnego
 */
class IndexController extends Mvc\Controller
{

    /**
     * @Inject
     * @var Auth
     */
    private $auth;

    /**
     * @Inject
     * @var HttpServerEnv
     */
    private $httpServerEnv;

    /**
     * @Inject
     * @var Session
     */
    private $session;

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
        if ($referer && $referer != $this->httpServerEnv->requestUri) {
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
        $this->getMessenger()->addMessage('messenger.index.password.success');
        //wylogowanie
        $this->auth->clearIdentity();
        $this->getResponse()->redirect('cmsAdmin');
    }

}
