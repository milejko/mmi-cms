<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler użytkownika
 */
class UserController extends \Mmi\Mvc\Controller
{

    /**
     * Logowanie
     */
    public function loginAction()
    {
        $form = new \Cms\Form\Login;
        $this->view->loginForm = $form;
        if (!$form->isMine()) {
            return;
        }
        //błędne logowanie
        if (!$form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.user.login.fail', false);
            return;
        }
        //lowowanie poprawne
        $this->getMessenger()->addMessage('messenger.user.login.success', true);
        \Cms\Model\Stat::hit('user-login');
        $referer = $this->getRequest()->getReferer();
        //przekierowanie na referer
        if ($referer && $referer != \Mmi\App\FrontController::getInstance()->getEnvironment()->requestUri) {
            return $this->getResponse()->redirectToUrl($referer);
        }
        $this->getResponse()->redirectToUrl('/');
    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        \App\Registry::$auth->clearIdentity();
        $this->getMessenger()->addMessage('messenger.user.logout.success', true);
        \Cms\Model\Stat::hit('user-logout');
        $this->getResponse()->redirectToUrl('/');
    }

    /**
     * Nowe konto
     */
    public function registerAction()
    {
        $form = new \Cms\Form\Register(new \Cms\Orm\CmsAuthRecord());
        $this->view->registerForm = $form;
        if (!$form->isMine()) {
            return;
        }
        //błędy formularza
        if (!$form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.user.form.errors', false);
            return;
        }
        //rejestracja poprawna
        $this->getMessenger()->addMessage('messenger.user.register.success', true);
        \Cms\Model\Stat::hit('user-register');
        $this->getResponse()->redirectToUrl('/');
    }

}
