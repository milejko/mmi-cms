<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

/**
 * Plugin front kontrolera (hooki)
 */
class Plugin extends \Mmi\Controller\Plugin\PluginAbstract {

	/**
	 * Hook przed routingiem
	 * dodaje do routera routy przechowywane w bazie CMS
	 * @param \Mmi\Controller\Request $request
	 */
	public function routeStartup(\Mmi\Controller\Request $request) {
		//routy z cms
		if (null === ($routes = \App\Registry::$cache->load('Mmi-Route'))) {
			//zapis rout do cache
			\App\Registry::$cache->save($routes = \Cms\Orm\Route\Query::active()->find(), 'Mmi-Route', 0);
		}
		//aktualizacja konfiguracji routera  routy CMS
		\Cms\Model\Route::updateRouterConfig(\Mmi\Controller\Front::getInstance()->getRouter()->getConfig(), $routes);
	}

	/**
	 * Przed uruchomieniem dispatchera
	 * @param \Mmi\Controller\Request $request
	 */
	public function preDispatch(\Mmi\Controller\Request $request) {
		//obsługa nieodnalezionych komponentów i nieprawidłowych języków
		$this->_handleNotFoundForward($request);

		//ustawianie sesji
		if (\App\Registry::$config->session->name) {
			//ustawia ID sesji jeśli jawnie podana w requescie
			$request->sessionId !== null ? \Mmi\Session::setId($request->sessionId) : null;
			\Mmi\Session::start(\App\Registry::$config->session);
		}

		//ustawianie widoku
		$this->_viewSetup($request);

		//konfiguracja autoryzacji
		$auth = new \Mmi\Auth();
		$auth->setSalt(\App\Registry::$config->salt);
		$auth->setModelName(\App\Registry::$config->session->authModel ? \App\Registry::$config->session->authModel : '\Cms\Model\Auth');
		\App\Registry::$auth = $auth;
		\Mmi\Controller\Action\Helper\Action::getInstance()->setAuth($auth);

		//funkcja pamiętaj mnie realizowana poprzez cookie
		$cookie = new \Mmi\Http\Cookie();
		$remember = \App\Registry::$config->session->authRemember ? \App\Registry::$config->session->authRemember : 0;
		if ($remember > 0 && !$auth->hasIdentity() && $cookie->match('remember')) {
			$params = [];
			parse_str($cookie->getValue(), $params);
			if (isset($params['id']) && isset($params['key']) && $params['key'] == md5(\App\Registry::$config->salt . $params['id'])) {
				$auth->setIdentity($params['id']);
				$auth->idAuthenticate();
			}
		}
		//autoryzacja do widoku
		if ($auth->hasIdentity()) {
			\Mmi\Controller\Front::getInstance()->getView()->auth = $auth;
		}

		//ustawienie acl
		if (null === ($acl = \App\Registry::$cache->load('Mmi-Acl'))) {
			$acl = \Cms\Model\Acl::setupAcl();
			\App\Registry::$cache->save($acl, 'Mmi-Acl', 0);
		}
		\App\Registry::$acl = $acl;
		\Mmi\Controller\Action\Helper\Action::getInstance()->setAcl($acl);
		\Mmi\Controller\Front::getInstance()->getView()->acl = $acl;

		//zablokowane na ACL
		if (!$acl->isAllowed($auth->getRoles(), strtolower($request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName()))) {
			//brak autoryzacji i kontroler admina - przekierowanie na logowanie
			if (!$auth->hasIdentity() && $request->getModuleName() == 'cmsAdmin') {
				//logowanie admina
				$this->_setAdminLoginRequest($request);
			} elseif (!$auth->hasIdentity()) {
				//logowanie użytkownika
				$this->_setUserLoginRequest($request);
			} else {
				//zalogowany na nieuprawnioną rolę
				$this->_setUnauthorizedRequest($request);
			}
		}
		//ustawienie nawigatora
		if (null === ($navigation = \App\Registry::$cache->load('Mmi-Navigation-' . $request->__get('lang')))) {
			/* @var $config \App\Config\Navigation */
			$config = \App\Registry::$config->navigation;
			\Cms\Model\Navigation::decorateConfiguration($config);
			$navigation = new \Mmi\Navigation($config);
			\App\Registry::$cache->save($navigation, 'Mmi-Navigation-' . $request->__get('lang'), 0);
		}
		$navigation->setup($request);
		//przypinanie nawigatora do helpera widoku nawigacji
		\Mmi\View\Helper\Navigation::setAcl($acl);
		\Mmi\View\Helper\Navigation::setAuth($auth);
		\Mmi\View\Helper\Navigation::setNavigation($navigation);
	}

	/**
	 * Obsługa przekierowania na błąd w przypadku nieprawidłowego języka lub
	 * braku modułu
	 * @param \Mmi\Controller\Request $request
	 */
	protected function _handleNotFoundForward(\Mmi\Controller\Request $request) {
		//niepoprawny język
		if ($request->__get('lang') && !in_array($request->__get('lang'), \App\Registry::$config->languages)) {
			\Mmi\Controller\Front::getInstance()->getResponse()->setCodeNotFound();
			//wyłączanie języka
			unset($request->lang);
			//ustawianie języka do domyślny
			if (isset(\App\Registry::$config->languages[0])) {
				$request->lang = \App\Registry::$config->languages[0];
			}
			//ustawianie błędu
			$this->_setNotFoundRequest($request);
		}
		//brak komponentu (moduł + kontroler + akcja)
		$components = \Mmi\Controller\Front::getInstance()->getStructure('module');
		if (!isset($components[$request->getModuleName()][$request->getControllerName()][$request->getActionName()])) {
			\Mmi\Controller\Front::getInstance()->getResponse()->setCodeNotFound();
			//ustawianie błędu
			$this->_setNotFoundRequest($request);
		}
	}

	/**
	 * Ustawienie zmiennych w widoku
	 * @param \Mmi\Controller\Request $request
	 */
	protected function _viewSetup(\Mmi\Controller\Request $request) {
		//ustawienie widoku
		$view = \Mmi\Controller\Front::getInstance()->getView();
		$base = $view->baseUrl;
		$view->domain = \App\Registry::$config->host;
		$view->languages = \App\Registry::$config->languages;
		$jsRequest = $request->toArray();
		$jsRequest['baseUrl'] = $base;
		unset($jsRequest['controller']);
		unset($jsRequest['action']);
		//umieszczenie tablicy w headScript()
		$view->headScript()->appendScript('var request = ' . json_encode($jsRequest));
	}

	/**
	 * Ustawia request na logowanie admina
	 * @param \Mmi\Controller\Request $request
	 */
	protected function _setAdminLoginRequest(\Mmi\Controller\Request $request) {
		$request->setModuleName('cmsAdmin')
			->setControllerName('index')
			->setActionName('login');
	}

	/**
	 * Ustawia request na logowanie admina
	 * @param \Mmi\Controller\Request $request
	 */
	protected function _setUserLoginRequest(\Mmi\Controller\Request $request) {
		$request->setModuleName('cms')
			->setControllerName('user')
			->setActionName('login');
	}

	/**
	 * Ustawia request na nieautoryzowany
	 * @param \Mmi\Controller\Request $request
	 */
	protected function _setUnauthorizedRequest(\Mmi\Controller\Request $request) {
		$request->setModuleName('mmi')
			->setControllerName('index')
			->setActionName('error')
			->setParams(['unauthorized' => 1]);
		//ustawianie kodu odpowiedzi na 403
		\Mmi\Controller\Front::getInstance()->getResponse()->setCodeForbidden();
	}

	/**
	 * Ustawia request na brak strony
	 * @param \Mmi\Controller\Request $request
	 */
	protected function _setNotFoundRequest(\Mmi\Controller\Request $request) {
		$request->setModuleName('mmi')
			->setControllerName('index')
			->setActionName('error');
		//ustawianie kodu odpowiedzi na 404
		\Mmi\Controller\Front::getInstance()->getResponse()->setCodeNotFound();
	}

}
