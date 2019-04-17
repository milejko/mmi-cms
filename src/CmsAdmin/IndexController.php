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
 * Kontroler główny panelu administracyjnego
 */
class IndexController extends Mvc\Controller
{

    /**
     * Strona główna admina
     */
    public function indexAction()
    {
        $this->view->user = (new \Cms\Orm\CmsAuthQuery)->findPk(\App\Registry::$auth->getId());
    }

    /**
     * Logowanie
     */
    public function loginAction()
    {
        //jesli zalogowany
        if (\App\Registry::$auth->hasIdentity()) {
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
        \Mmi\Session\Session::regenerateId();
        //zalogowano
        $this->getMessenger()->addMessage('messenger.index.login.success', true);
        \Cms\Model\Stat::hit('admin-login');
        $referer = $this->getRequest()->getReferer();
        //przekierowanie na referer
        if ($referer && $referer != \Mmi\App\FrontController::getInstance()->getEnvironment()->requestUri) {
            return $this->getResponse()->redirectToUrl($referer);
        }
        $this->getResponse()->redirect('cmsAdmin');
    }

    /**
     * Akcja wylogowania
     */
    public function logoutAction()
    {
        \App\Registry::$auth->clearIdentity();
        $this->getMessenger()->addMessage('messenger.index.logout.success', true);
        //hit do statystyk
        \Cms\Model\Stat::hit('admin-logout');
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
        //hit do statystyk
        \Cms\Model\Stat::hit('admin_password');
        $this->getMessenger()->addMessage('messenger.index.password.success');
        //wylogowanie
        \App\Registry::$auth->clearIdentity();
        \Cms\Model\Stat::hit('admin_logout');
        $this->getResponse()->redirect('cmsAdmin');
    }

}
