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
class IndexController extends Mvc\Controller {

	/**
	 * Strona główna admina
	 */
	public function indexAction() {
		$this->view->user = (new \Cms\Orm\CmsAuthQuery)->findPk(\App\Registry::$auth->getId());
	}

	/**
	 * Logowanie
	 */
	public function loginAction() {
		$form = new \CmsAdmin\Form\Login;
		$this->view->loginForm = $form;
		//brak wysłanych danych
		if (!$form->isMine()) {
			return;
		}
		//logowanie niepoprawne
		if (!$form->isSaved()) {
			$this->getMessenger()->addMessage('Logowanie niepoprawne', false);
			return;
		}
		//zalogowano
		$this->getMessenger()->addMessage('Zalogowano poprawnie', true);
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
	public function logoutAction() {
		\App\Registry::$auth->clearIdentity();
		$this->getMessenger()->addMessage('Dziękujemy za skorzystanie z serwisu, wylogowanio poprawnie', true);
		//hit do statystyk
		\Cms\Model\Stat::hit('admin-logout');
		$this->getResponse()->redirect('cmsAdmin');
	}

	/**
	 * Akcja ustawiania języka (w sesji)
	 */
	public function languageAction() {
		$session = new \Mmi\Session\SessionSpace('cms-language');
		$session->lang = in_array($this->locale, \App\Registry::$config->languages) ? $this->locale : null;
		$referer = \Mmi\App\FrontController::getInstance()->getRequest()->getReferer();
		//przekierowanie na referer
		if ($referer) {
			$this->getResponse()->redirectToUrl($referer);
		}
		$this->getResponse()->redirect('cmsAdmin');
	}

	/**
	 * Widget języków
	 */
	public function languageWidgetAction() {
		$this->view->languages = \App\Registry::$config->languages;
	}

	/**
	 * Zmiana hasła
	 */
	public function passwordAction() {
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
		$this->getMessenger()->addMessage('Hasło zmienione poprawnie, zaloguj się ponownie');
		//wylogowanie
		\App\Registry::$auth->clearIdentity();
		\Cms\Model\Stat::hit('admin_logout');
		$this->getResponse()->redirect('cmsAdmin');
		
	}

}
